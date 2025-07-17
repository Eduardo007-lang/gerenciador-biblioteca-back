<?php

namespace App\Http\Api\V1\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $validated = $validator->validate();
        $result = $this->authService->login($validated['email'], $validated['password']);

        if (!$result['success']) {
            $status = $result['message'] === 'Credenciais inválidas' ? 401 : 500;
            return response()->json(['error' => $result['message']], $status);
        }
        // Retornar apenas o token JWT
        return response()->json(['token' => $result['data']['token']]);
    }

    public function me()
    {
        return response()->json($this->authService->me());
    }

    public function logout()
    {
        $this->authService->logout(Auth::user());
        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }
} 