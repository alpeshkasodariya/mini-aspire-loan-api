<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
 

class RegisterTest extends TestCase
{
    use WithFaker;
    /**
     * A basic test example.
     *
     * @return void
     */
    /*
        test to register with email and password
        expected return 200 
    */
    public function test_register_successfully()
    {
        $payload = [
            'name' => 'Admin John',
            'email' => 'john@john.com',
            'password' =>'secret',
        ]; 
        
         
       
        $this->json('POST', '/api/register', $payload , ['Accept' => 'application/json'])
                ->assertStatus(200)
                 ->assertJsonStructure([ 
                    "success",
                    "message",
                     "data" => [ 
                     'name',
                     'email',
                     'updated_at',
                     'created_at',
                     'id',
                 ]
                
            ]); 
       
    }
    
   
}
