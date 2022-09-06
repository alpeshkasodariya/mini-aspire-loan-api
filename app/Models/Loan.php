<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 

class Loan extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'amount', 'term', 'repayment_frequency', 'status'];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scheduleRepayment(){
        return $this->hasMany(ScheduleRepayment::class);
    }
   
    public function amountLeft(){
        $debt = $this->scheduleRepayment()->where('status','PAID')->sum('amount_paid');
        return $debt;
    }
}
