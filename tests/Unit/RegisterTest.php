<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;


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
    public function test_register_with_email_pass()
    {
        $payload = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
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
    
   /*
        test to register without email and password
        expected return error 
    */
    public function tests_require_email()
    {
        $payload = ['name' => $this->faker->name(),'password' =>'secret'];
        $this->json('POST', '/api/register',$payload)
            ->assertStatus(200)
            ->assertJson([
            'error' => [  
                 'email' => [
                      "The email field is required."
                 ]
             ]
       ]);
    }
    
    /*
        test to register without email and password
        expected return error 
    */
    public function tests_requires_name()
    {
        $payload = ['email' => $this->faker->unique()->safeEmail(),'password' =>'secret'];
        $this->json('POST', '/api/register',$payload)
            ->assertStatus(200)
            ->assertJson([
            'error' => [  
                 'name' => [
                      "The name field is required."
                 ]
             ]
       ]);
    }
    
    /*
        test to register without email and password
        expected return error 
    */
    public function tests_requires_password()
    {
        $payload = ['name' => $this->faker->name(),'email' => $this->faker->unique()->safeEmail()];
        $this->json('POST', '/api/register',$payload)
            ->assertStatus(200)
            ->assertJson([
            'error' => [  
                 'password' => [
                      "The password field is required."
                 ]
             ]
       ]);
    }

    /*
        test to register without email and password
        expected return error 
    */
    public function tests_requires_password_email_and_name()
    {
        $this->json('POST', '/api/register')
            ->assertStatus(200)
            ->assertJson([
            'error' => [ 
                 'name' => [
                      "The name field is required."
                 ],
                 'email' => [
                      "The email field is required."
                 ],
                'password' => [
                      "The password field is required."
                 ]
             ]
       ]);
    }
}
