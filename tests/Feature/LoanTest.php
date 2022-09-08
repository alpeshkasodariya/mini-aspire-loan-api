<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Loan;
use App\Models\User;

class LoanTest extends TestCase
{
     /*
        test to create loan with authentication
        expected return 200 with status true and message Loan created
    */
    public function test_create_loan_with_auth()
    {
        $loan = Loan::factory()->create();
        $user = User::factory()->create(); 
        $response = $this->actingAs($user, 'api')->json('POST','/api/loans', $loan->toArray());
        $response->assertStatus(200); 
    }
    
    
       /*
        test to get all loan for this user with authentication
        expected return 200 with collection of Loan owned by this user
    */
    public function test_get_all_loan_with_auth()
    {
        $user = User::factory()->create(); 
        $response = $this->actingAs($user, 'api')->json('GET','/api/loans');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
            '*' => [
                'id', 
                'user', 
                'amount', 
                'term', 
                'repayment_freq',  
                'status', 
                'created_at', 
                'updated_at', 
                'amount_left', 
                'repayments', 
                ]
            ]
        ]);
    }
}
