<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         
        return [
            'id' => $this->id,
            'user' => $this->user,
            'amount' => $this->amount,
            'term' => $this->term,
            'repayment_frequency' => $this->repayment_frequency, 
            'status' => $this->status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'amount_left' => $this->amountLeft(),
            'repayments' => $this->scheduleRepayment
        ];
    }
}
