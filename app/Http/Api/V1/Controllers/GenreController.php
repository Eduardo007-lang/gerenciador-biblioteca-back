<?php
/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Autenticação JWT usando Bearer token",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 */

namespace App\Http\Api\V1\Controllers;

use App\Services\GenreService;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(
 *     name="Genres",
 *     description="Endpoints para gerenciamento de gêneros"
 * )
 */
class GenreController extends Controller
{
    protected $genreService;

    public function __construct(GenreService $genreService)
    {
        $this->genreService = $genreService;
    }

    /**
     * @OA\Get(
     *     path="/api/V1/genres",
     *     tags={"Genres"},
     *     summary="Lista todos os gêneros",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de gêneros")
     * )
     */
    public function index()
    {
        return response()->json($this->genreService->listGenres());
    }

    /**
     * @OA\Post(
     *     path="/api/V1/genres",
     *     tags={"Genres"},
     *     summary="Cria um novo gênero",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"genre"},
     *             @OA\Property(property="genre", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Gênero criado")
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'genre' => 'required|string|max:255|unique:genres,genre',
            ]);
            $validatedData = $validator->validate();
            $genre = $this->genreService->createGenre($validatedData);
            return response()->json($genre, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 409);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/V1/genres/{id}",
     *     tags={"Genres"},
     *     summary="Atualiza um gênero existente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"genre"},
     *             @OA\Property(property="genre", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Gênero atualizado")
     * )
     */
    public function update(Request $request, Genre $genre)
    {
        try {
            $validator = Validator::make($request->all(), [
                'genre' => 'required|string|max:255|unique:genres,genre,' . $genre->id,
            ]);
            $validatedData = $validator->validate();
            $updatedGenre = $this->genreService->updateGenre($genre, $validatedData);
            return response()->json($updatedGenre, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 409);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/V1/genres/{id}",
     *     tags={"Genres"},
     *     summary="Exclui um gênero",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Gênero excluído")
     * )
     */
    public function destroy(Genre $genre)
    {
        try {
            $this->genreService->deleteGenre($genre);
            return response()->json(['message' => 'Gênero deletado com sucesso'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gênero não encontrado.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao deletar gênero: ' . $e->getMessage()
            ], 500);
        }
    }
} 