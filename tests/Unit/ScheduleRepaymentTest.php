<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Loan;
use App\Models\ScheduleRepayment;
use App\Models\User;

class ScheduleRepaymentTest extends TestCase
{
    use WithFaker;
    
     /*
        test to create one repayment for this user without authentication
        expected return 401 with message Unauthenticated.
    */
    public function test_create_repayment_without_Auth()
    {
        $response = $this->json('POST', '/api/schedulepay/0');
        $response->assertStatus(401);
        $response->assertJson(['error' => 'Token not parsed']);
    }

    /*
        test to create one repayment for other user with authentication
        expected return 403 with message You can only update your own loans.
    */
    public function test_create_other_repayment_with_auth()
    {
        $user1 = User::factory()->create();
        $loan1 = Loan::factory()->create([
            'user_id' => $user1->id,
        ]);
        $user2 = User::factory()->create();
        $response = $this->actingAs($user2, 'api')->json('POST', '/api/schedulepay/'.$loan1->id);
        $response->assertStatus(403);
        $response->assertJson(['message' => 'You can only update your own loans.']);
    }
    
    /*
        test to create one repayment for non approved Loan owned by this user with authentication
        expected return 403 with message Your loan status is not Accepted.
    */
    public function test_create_pending_repayment_with_Auth()
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'status' => 'PENDING',
        ]);
        $response = $this->actingAs($user, 'api')->json('POST', '/api/schedulepay/'.$loan->id);
        $response->assertStatus(403);
        $response->assertJson(['message' => 'Your loan status is not APPROVED.']);
    }

    /*
        test to create one repayment for approved Loan owned by this user with authentication
        expected return 200
    */
    public function test_create_approved_schedulepay_with_auth()
    {
        $user = User::factory()->create(); 
        $loan = Loan::factory()->approved()->make(); 
        $create_loan_response = $this->actingAs($user, 'api')->json('POST','/api/loans', $loan->toArray());
        $create_loan_data_content = json_decode($create_loan_response->getContent(), true); 
        $create_loan_data = $create_loan_data_content['data']; 
        $loanobj = Loan::find($create_loan_data['id']);
        $loanobj->status="APPROVED";
        $loanobj->update();
         
        $payload = ['amount' => ($create_loan_data['amount']/$create_loan_data['term'])];
        $response = $this->actingAs($user, 'api')->json('POST', '/api/schedulepay/'.$create_loan_data['id'],$payload);
        $responsedata = json_decode($response->getContent(), true);
        $response->assertJson($responsedata); 
        $response->dump();
        
    }

    
     
}
