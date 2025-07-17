<?php

use Illuminate\Support\Facades\Route;
use App\Http\Api\V1\Controllers\AuthController;
use App\Http\Api\V1\Controllers\BookController;
use App\Http\Api\V1\Controllers\GenreController;
use App\Http\Api\V1\Controllers\LoanController;
use App\Http\Api\V1\Controllers\UserController;

// Rota pública de login
Route::post('login', [AuthController::class, 'login']);

// Rotas protegidas por JWT
Route::middleware('auth:api')->prefix('V1')->group(function () {
    // Auth
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Users
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    // Books
    Route::get('books', [BookController::class, 'index']);
    Route::post('books', [BookController::class, 'store']);
    Route::put('books/{book}', [BookController::class, 'update']);
    Route::delete('books/{book}', [BookController::class, 'destroy']);

    // Genres
    Route::get('genres', [GenreController::class, 'index']);
    Route::post('genres', [GenreController::class, 'store']);
    Route::put('genres/{genre}', [GenreController::class, 'update']);
    Route::delete('genres/{genre}', [GenreController::class, 'destroy']);

    // Loans
    Route::get('loans', [LoanController::class, 'index']);
    Route::post('loans', [LoanController::class, 'store']);
    Route::put('loans/{loan}', [LoanController::class, 'update']);
    Route::delete('loans/{loan}', [LoanController::class, 'destroy']);
});    

Route::get('teste', function() {
    return 'ok';
});    


