<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\Loan;
use App\Models\ScheduleRepayment;
use App\Http\Resources\LoanResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller {

    public function __construct() {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $loan = $request->user()->loans()->get();
        return LoanResource::collection($loan);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //Validate data
        $data = $request->only('amount', 'term');
        $validator = Validator::make($data, [
                    'amount' => 'required',
                    'term' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        //Request is valid, create new loan
        $loan = Loan::create([
                    'user_id' => $request->user()->id,
                    'amount' => $request->amount,
                    'term' => $request->term,
                    'repayment_frequency' => "weekly",
                    'status' => "PENDING",
        ]);

        //Generate schedule amount based on term and amount 
        $scheduleRepaymentAmount = $request->amount / 3;

        //Create schedule repayment based on term with weekly schedule
        for ($t = 1; $t <= $request->term; $t++) {
            $scheduledateObj = Carbon::today()->addDays(7 * $t);
            $scheduledate = $scheduledateObj->toDateString();
            $repayment_schedule = ScheduleRepayment::create([
                        'user_id' => $request->user()->id,
                        'loan_id' => $loan->id,
                        'amount' => $scheduleRepaymentAmount,
                        'schedule_date' => $scheduledate,
                        'status' => "PENDING",
            ]);
        }

        //Loan and Schedule repayment created, return success response
        return response()->json([
                    'status' => (bool) $loan,
                    'message' => $loan ? 'Loan created' : 'Error creating loan',
                    'data' => new LoanResource($loan),
                        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Loan $loan) {
        // check if currently authenticated user is the owner of the loan
        if ($request->user()->id !== $loan->user_id) {
            return response()->json(['message' => 'You can only see your own loans.'], 403);
        }

        return new LoanResource($loan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan) {
        // check if currently authenticated user is the owner of the loan
        if ($request->user()->type == "Admin") {
            if (isset($request->status)) {
                //Admin approve the loan
                $loan->status = $request->status;
            }
        } else {
            if ($request->user()->id !== $loan->user_id) {
                return response()->json(['message' => 'Only Admin can update status'], 403);
            }
            if ($loan->status != 'Pending') {
                return response()->json(['message' => 'Your loan status is not editable.', 'status' => $loan->status], 403);
            }
        }

        $loan->save();

        return new LoanResource($loan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
