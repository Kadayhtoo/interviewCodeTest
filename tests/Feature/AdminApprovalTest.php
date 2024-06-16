<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Mail\UserApprovedMail;
use Illuminate\Support\Facades\Mail;

class AdminApprovalTest extends TestCase
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
    public function test_approve_user()
    {
        // Create a user
        $user = User::factory()->create(['role' => 'user']);

        // Mock the mail sending
        Mail::fake();

        // Act as admin and approve the user
        $response = $this->actingAsAdmin()->get(route('approve', $user->id));

        // Assert the response status
        $response->assertStatus(200);

        // Assert the user is approved
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_approved' => true,
        ]);

        // Assert an email was sent
        Mail::assertSent(UserApprovedMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
    protected function actingAsAdmin()
    {
        // Create an admin user
        $admin = User::where('role','admin')->first();

        // Act as the admin user
        return $this->actingAs($admin);
    }
}
