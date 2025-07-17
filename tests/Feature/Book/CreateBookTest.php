<?php

namespace Tests\Feature\Book;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_book()
    {
        $user = User::factory()->create([
            'password' => Hash::make('Admin1234!'),
            'registration_number' => '123456'
        ]);

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/V1/books', [
                'name' => 'Livro Teste',
                'author' => 'Autor Teste',
                'registration_number' => '999999',
                'status' => 'available'
            ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['message' => 'Livro criado com sucesso.']);
    }
} 