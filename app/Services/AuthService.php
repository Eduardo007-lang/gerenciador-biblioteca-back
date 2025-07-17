<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $email, string $password): array
    {
        try {
            $credentials = ['email' => $email, 'password' => $password];
            if (!$token = auth('api')->attempt($credentials)) {
                return [
                    'success' => false,
                    'message' => 'Credenciais inválidas',
                    'data' => null
                ];
            }
            $user = auth('api')->user();
            return [
                'success' => true,
                'message' => 'Login realizado com sucesso',
                'data' => [
                    'token' => $token,
                    'user' => $user
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function me()
    {
        return Auth::guard('api')->user();
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
} 