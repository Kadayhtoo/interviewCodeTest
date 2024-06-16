<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use \Crypt;

class UserLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function testUserLogin()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@gmail.com',
            'password' => 'admin1234',
        ]);

        $token = auth()->attempt([
            'email' => 'admin@gmail.com',
            'password' => 'admin1234',
        ]);

        $response->assertStatus(200)->assertJson([
            'access_token' => $token,
            'token_type' => 'bearer',
            'message' => 'Login successful']);
    }
}
