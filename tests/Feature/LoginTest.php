<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /*
      test to login with email and password
      expected return 200
     */
    public function test_login_with_email_pass()
    {

        $payload = ['email' => 'john@john.com', 'password' => 'secret'];

        $this->json('POST', '/api/login', $payload)
                ->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'token',
        ]);

         
}
}
