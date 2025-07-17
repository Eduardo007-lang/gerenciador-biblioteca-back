<?php

namespace App\Http\Api\V1\Controllers;

use App\Services\BookService;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(
 *     name="Books",
 *     description="Endpoints para gerenciamento de livros"
 * )
 */
class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * @OA\Get(
     *     path="/api/V1/books",
     *     tags={"Books"},
     *     summary="Lista todos os livros",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de livros")
     * )
     */
    public function index()
    {
        return response()->json($this->bookService->listBooks());
    }

    /**
     * @OA\Post(
     *     path="/api/V1/books",
     *     tags={"Books"},
     *     summary="Cria um novo livro",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","author","registration_number","status"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="author", type="string"),
     *             @OA\Property(property="registration_number", type="integer"),
     *             @OA\Property(property="status", type="string", enum={"available","borrowed"}),
     *             @OA\Property(property="genres", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Livro criado")
     * )
     */
    public function store(Request $request)
    {
        try {
            
            if ($request->has('registration_number')) {
                $request->merge([
                    'registration_number' => (string) $request->input('registration_number')
                ]);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'registration_number' => 'required|string|unique:books,registration_number|max:50',
                'status' => 'required|string|in:available,borrowed',
                'genres' => 'array',
                'genres.*' => 'string|exists:genres,id',
            ]);
            $validatedData = $validator->validate();
            $book = $this->bookService->createBook($validatedData);
            return response()->json(['message' => 'Livro criado com sucesso.', 'data' => $book], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/V1/books/{id}",
     *     tags={"Books"},
     *     summary="Atualiza um livro existente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","author","registration_number","status"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="author", type="string"),
     *             @OA\Property(property="registration_number", type="integer"),
     *             @OA\Property(property="status", type="string", enum={"available","borrowed"}),
     *             @OA\Property(property="genres", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Livro atualizado")
     * )
     */
    public function update(Request $request, Book $book)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'registration_number' => 'required|string|max:50',
                'status' => 'required|string|in:available,borrowed',
                'genres' => 'array',
                'genres.*' => 'string|exists:genres,id',
            ]);
            $validatedData = $validator->validate();
            $book = $this->bookService->updateBook($book, $validatedData);
            return response()->json([
                'success'=>true,
                'message' =>'Livro Atualizado com sucesso',
                'data' => $book
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/V1/books/{id}",
     *     tags={"Books"},
     *     summary="Exclui um livro",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Livro excluído")
     * )
     */
    public function destroy(string $id)
    {
        try {
            $this->bookService->deleteBook($id);
            return response()->json(['message' => 'Livro deletado com sucesso'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Livro não encontrado.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao deletar livro: ' . $e->getMessage()
            ], 500);
        }
    }
} 