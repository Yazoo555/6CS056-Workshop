<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_register_with_default_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertRedirect('/courses');
        $this->assertAuthenticated();

        $user = User::where('email', 'new@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('user', $user->role);
        $this->assertNotSame('Password@123', $user->password);
        $this->assertTrue(Hash::check('Password@123', $user->password));
    }

    public function test_registration_cannot_assign_admin_role(): void
    {
        $this->post('/register', [
            'name' => 'Sneaky User',
            'email' => 'sneaky@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'sneaky@example.com',
            'role' => 'user',
        ]);
    }

    public function test_registration_validates_input(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'taken@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Different@123',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    public function test_registration_rejects_invalid_email_and_missing_fields(): void
    {
        $this->post('/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '',
            'password_confirmation' => '',
        ])->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => 'Password@123']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password@123',
        ]);

        $response->assertRedirect('/courses');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create(['password' => 'Password@123']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_session_is_regenerated_after_login(): void
    {
        $user = User::factory()->create(['password' => 'Password@123']);

        $this->get('/login');
        $previousSessionId = session()->getId();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password@123',
        ]);

        $this->assertNotSame($previousSessionId, session()->getId());
    }

    public function test_protected_routes_require_authentication(): void
    {
        $this->get('/courses')->assertRedirect('/login');
        $this->get('/courses/create')->assertRedirect('/login');
        $this->get('/students')->assertRedirect('/login');
        $this->get('/students/create')->assertRedirect('/login');
    }

    public function test_user_can_logout_and_lose_access(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post('/logout')->assertRedirect('/login');

        $this->assertGuest();
        $this->get('/courses')->assertRedirect('/login');
    }
}
