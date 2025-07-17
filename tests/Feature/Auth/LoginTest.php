<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_token_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin1234!'),
            'registration_number' => '123456'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@gmail.com',
            'password' => 'Admin1234!'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }
} 