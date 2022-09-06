<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\Loan;
use App\Models\ScheduleRepayment;
use App\Http\Resources\ScheduleRepaymentResource;

class ScheduleRepaymentController extends Controller {

    public function __construct() {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request, Loan $loan) {
        // check if current authenticated user is the owner of the loan
        if ($request->user()->id !== $loan->user_id) {
            return response()->json(['message' => 'You can only update your own loans.'], 403);
        }
        
        if ($loan->status == 'APPROVED') {

            $schedule_repay = ScheduleRepayment::where('user_id', $request->user()->id)
                            ->where('loan_id', $loan->id)
                            ->where('status', 'PENDING')
                            ->orderBy('id', 'ASC')->first();
            
            if ($schedule_repay) {
                // update schedule_repay status and paid amount
                $schedule_repay->status = "PAID";
                $schedule_repay->amount_paid = $request->amount;
                $schedule_repay->update();
            }
            if ($loan->scheduleRepayment()->sum('amount') <= $loan->amountLeft()) {
                // update loan status to PAID when all schedule repayment done 
                $loan->status = 'PAID';
                $loan->save();
            }
            if ($schedule_repay) {
                return new ScheduleRepaymentResource($schedule_repay);
            }
        } else if ($loan->status == 'PENDING') {
            return response()->json(['message' => 'Your loan status is not APPROVED.'], 403);
        } else {
            return response()->json(['message' => 'Your loan status is PAID.'], 403);
        }
        return response()->json(['message' => 'Your loan status is not APPROVED.'], 403);
    }

}
