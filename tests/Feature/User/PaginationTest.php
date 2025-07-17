<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_list_is_paginated()
    {
        $user = User::factory()->create();
        User::factory()->count(15)->create();

        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/V1/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'current_page',
                     'data',
                     'first_page_url',
                     'from',
                     'last_page',
                     'last_page_url',
                     'links',
                     'next_page_url',
                     'path',
                     'per_page',
                     'prev_page_url',
                     'to',
                     'total'
                 ]);
    }
} 