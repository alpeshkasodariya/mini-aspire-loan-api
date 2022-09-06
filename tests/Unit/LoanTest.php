<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Loan;
use App\Models\User;
use App\Http\Resources\LoanResource;

class LoanTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    
    /*
        test to create loan without authentication
        expected return 401 with message Unauthenticated.
    */
    public function test_create_loan_without_auth()
    {
        $loan = Loan::factory()->make();
        $response = $this->json('POST','/api/loans', $loan->toArray());
        $response->assertStatus(401);
        $response->assertJson(['error' => 'Token not parsed']);
    }
    
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
        test to get all loan for this user without authentication
        expected return 401 with message Unauthenticated.
    */
    public function test_get_all_loan_without_auth()
    {
        $response = $this->json('GET','/api/loans');
        $response->assertStatus(401);
        $response->assertJson(['error' => 'Token not parsed']);
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
    
    /*
        test to get one loan for this user without authentication
        expected return 401 with message Unauthenticated.
    */
    public function test_get_one_loan_without_auth()
    {
        $loan = Loan::factory()->make();
        $response = $this->json('GET', '/api/loans/'.$loan->id);
        $response->assertStatus(401);
        $response->assertJson(['error' => 'Token not parsed']);
    }
    
     /*
        test to get one exist loan for this user with authentication
        expected return 200 with one loan data
    */
    public function test_get_one_exist_loan_with_auth()
    { 
        $user = User::factory()->create(); 
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($user, 'api')->json('GET', '/api/loans/'.$loan->id);
        $response->assertStatus(200);
         
    }
    
    /*
        test to get one non exist loan for this user with authentication
        expected return 404 with message No query results for model [App\\Loan].
    */
    public function test_get_one_non_exist_loan_with_auth()
    {
        $user = User::factory()->create(); 
        $response = $this->actingAs($user, 'api')->json('GET', '/api/loans/0');
        $response->assertStatus(404);
        
    }
    
    /*
        test to get one other's loan
        expected return 403 with error You can only see your own loans.
    */
    public function test_get_one_other_loan_with_auth()
    {
        $user1 = User::factory()->create(); 
        $loan1 = Loan::factory()->create([
            'user_id' => $user1->id,
        ]);
        $user2 = User::factory()->create();
        $loan2 = Loan::factory()->create([
            'user_id' => $user2->id,
        ]);
        $response = $this->actingAs($user1, 'api')->json('GET', '/api/loans/'.$loan2->id);
        $response->assertStatus(403);
        $response->assertJson(['message' => 'You can only see your own loans.']);
    }
    
    /*
        test to update one exist loan for this user without authentication
        expected return 401 with message Unauthenticated.
    */
    public function test_update_loan_without_auth()
    {
        $response = $this->json('PATCH', '/api/loans/0');
        $response->assertStatus(401);
        $response->assertJson(['error' => 'Token not parsed']);
    }
    
     /*
        test to update one exist loan for this user with authentication
        expected return 200 with correct data loan and user
    */
    public function test_update_loan_with_auth()
    {
        $user =  User::where('type','admin')->first();
        $loan = Loan::factory()->create([ 
            'status' => 'APPROVED',
        ]);
        $response = $this->actingAs($user, 'api')->json('PATCH', '/api/loans/'.$loan->id);
        $response->assertStatus(200);
        
    }
    
    
    /*
        test to update one exist Accepted loan owned by this user with authentication
        expected return 403 with error You can only edit your own loans.
    */
    public function test_update_accepted_loan_with_auth()
    {
        $user = User::factory()->create(); 
        $loan = Loan::factory()->create([ 
            'status' => 'APPROVED',
        ]);
        $response = $this->actingAs($user, 'api')->json('PATCH', '/api/loans/'.$loan->id);
        $response->assertStatus(403);
        $response->assertJson(['message' => 'Only Admin can update status']);
    }
    
     

}
