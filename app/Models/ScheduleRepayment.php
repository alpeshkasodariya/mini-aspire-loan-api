<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleRepayment extends Model
{
    protected $fillable = ['user_id', 'loan_id', 'amount', 'schedule_date', 'status'];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function loan(){
        return $this->belongsTo(Loan::class);
    }
}
