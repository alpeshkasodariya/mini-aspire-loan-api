<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    /*
        test to login without email and password
        expected return 401 with error Unauthorized
    */
    public function test_login_without_email_pass()
    {
        $response = $this->json('POST','/api/login');
        $response->assertStatus(200);
        $response->assertJson([
            'error' => [
                 'email' => [
                      "The email field is required."
                 ],
                'password' => [
                      "The password field is required."
                 ]
             ]
       ]);
    }

    /*
        test to login without password
        expected return 401 with error Unauthorized
    */
    public function test_login_without_pass()
    {
        $payload = ['email' => 'john@example.com'];
        $response = $this->json('POST','/api/login', $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'error' => [ 
                'password' => [
                      "The password field is required."
                 ]
             ]
       ]);
    }

    /*
        test to login with email and password
        expected return 200 
    */
    public function test_login_with_email_pass()
    {
         
        $payload = ['email' => 'john@example.com', 'password' => 'secret'];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                    'success',
                    'token',
            ]);
    }
}
