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

use App\Services\LoanService;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Tag(
 *     name="Loans",
 *     description="Endpoints para gerenciamento de empréstimos"
 * )
 */
class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * @OA\Get(
     *     path="/api/V1/loans",
     *     tags={"Loans"},
     *     summary="Lista todos os empréstimos",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista de empréstimos")
     * )
     */
    public function index()
    {
        return response()->json($this->loanService->listLoans());
    }

    /**
     * @OA\Post(
     *     path="/api/V1/loans",
     *     tags={"Loans"},
     *     summary="Cria um novo empréstimo",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","book_id","loan_date","return_date","status"},
     *             @OA\Property(property="user_id", type="string"),
     *             @OA\Property(property="book_id", type="string"),
     *             @OA\Property(property="loan_date", type="string", format="date"),
     *             @OA\Property(property="return_date", type="string", format="date"),
     *             @OA\Property(property="status", type="string", enum={"pending","returned","overdue"})
     *         )
     *     ),
     *     @OA\Response(response=201, description="Empréstimo criado")
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|string|exists:users,id',
                'book_id' => 'required|string|exists:books,id',
                'loan_date' => 'required|date_format:Y-m-d',
                'return_date' => 'required|date_format:Y-m-d|after_or_equal:loan_date',
                'status' => 'required|string|in:pending,returned,overdue'
            ]);
            $validatedData = $validator->validate();
            if (isset($validatedData['return_date'])) {
                $validatedData['devolution_date'] = $validatedData['return_date'];
                unset($validatedData['return_date']);
            }
            $loan = $this->loanService->createLoan($validatedData);
            return response()->json($loan, 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/V1/loans/{id}",
     *     tags={"Loans"},
     *     summary="Atualiza um empréstimo existente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","book_id","loan_date","return_date","status"},
     *             @OA\Property(property="user_id", type="string"),
     *             @OA\Property(property="book_id", type="string"),
     *             @OA\Property(property="loan_date", type="string", format="date"),
     *             @OA\Property(property="return_date", type="string", format="date"),
     *             @OA\Property(property="status", type="string", enum={"pending","returned","overdue"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Empréstimo atualizado")
     * )
     */
    public function update(Request $request, Loan $loan)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|string|exists:users,id',
                'book_id' => 'required|string|exists:books,id',
                'loan_date' => 'required|date_format:Y-m-d',
                'return_date' => 'required|date_format:Y-m-d|after_or_equal:loan_date',
                'status' => 'required|string|in:pending,returned,overdue'
            ]);
            $validatedData = $validator->validate();
            if (isset($validatedData['return_date'])) {
                $validatedData['devolution_date'] = $validatedData['return_date'];
                unset($validatedData['return_date']);
            }
            $loan = $this->loanService->updateLoan($loan, $validatedData);
            return response()->json($loan, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/V1/loans/{id}",
     *     tags={"Loans"},
     *     summary="Exclui um empréstimo",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Empréstimo excluído")
     * )
     */
    public function destroy(Loan $loan)
    {
        try {
            $this->loanService->deleteLoan($loan);
            return response()->json(['message' => 'Empréstimo deletado com sucesso'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Empréstimo não encontrado.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Erro ao deletar empréstimo: ' . $e->getMessage()
            ], 500);
        }
    }
} 