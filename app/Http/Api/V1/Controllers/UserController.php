<?php

namespace App\Http\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Endpoints para gerenciamento de usuários"
 * )
 */
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/V1/users",
     *     tags={"Users"},
     *     summary="Lista todos os usuários",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de usuários")
     * )
     */
    public function index()
    {
        return response()->json($this->userService->listUser());
    }

    /**
     * @OA\Post(
     *     path="/api/V1/users",
     *     tags={"Users"},
     *     summary="Cria um novo usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","registration_number","password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="registration_number", type="integer"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuário criado")
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'registration_number' => 'required',
                'password' => 'required'
            ]);
            $validatedData = $validator->validate();
            $user = $this->userService->createUser($validatedData);
            return response()->json(['message' => 'Usuário criado com sucesso', 'data' => $user], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/V1/users/{id}",
     *     tags={"Users"},
     *     summary="Exibe um usuário específico",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Usuário encontrado"),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->getUserById($id);
            return response()->json(['data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/V1/users/{id}",
     *     tags={"Users"},
     *     summary="Atualiza um usuário existente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","registration_number","password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="registration_number", type="integer"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Usuário atualizado")
     * )
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'registration_number' => 'required',
                'password' => 'required'
            ]);
            $validatedData = $validator->validate();
            $user = $this->userService->updateUser($id, $validatedData);
            return response()->json(['message' => 'Usuário atualizado com sucesso', 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/V1/users/{id}",
     *     tags={"Users"},
     *     summary="Exclui um usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Usuário excluído")
     * )
     */
    public function destroy(string $id)
    {
        try {
            $this->userService->deleteUser($id);
            return response()->json(['message' => 'Usuário deletado com sucesso'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Usuário não encontrado.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao deletar usuário: ' . $e->getMessage()
            ], 500);
        }
    }
} 