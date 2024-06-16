<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use \Crypt;

class UserTest extends TestCase
{

    use WithFaker;
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
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = $this->makeFaker();
    }

    public function testUserRegistration()
    {
        $response = $this->postJson('/register', [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'role'=>'user',
            'profile_photo' =>UploadedFile::fake()->image('photo.jpg'),
            'id_document' =>UploadedFile::fake()->create('document.pdf'),
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Registration successful. Please wait for admin approval.']);
    }
}
