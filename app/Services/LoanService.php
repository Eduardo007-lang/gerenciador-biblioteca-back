<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LoanService
{
    const LOANS_CACHE_KEY = 'all_loans';

    public function listLoans()
    {
        $page = request('page', 1);
        $cacheKey = self::LOANS_CACHE_KEY . '_page_' . $page;
        return Cache::remember($cacheKey, 60 * 60, function () {
            return Loan::with(['user', 'book'])->paginate(10);
        });
    }

    public function createLoan(array $data)
    {
        $book = Book::find($data['book_id']);
        $userId = request()->user()?->id;

        if (!$book) {
            throw new \Exception('Livro não encontrado.');
        }

        if ($book->status === 'borrowed') {
            throw new \Exception('Livro não disponível para empréstimo.');
        }

        if($userId === $book->user_id) {
            throw new \Exception('Usuário proprietário do livro.');
        }

        $loan = Loan::create($data);
        
        $book->status = 'borrowed';
        $book->save();

        Cache::forget(self::LOANS_CACHE_KEY);
        return $loan->load('user', 'book');
    }
    
    public function updateLoan(Loan $loan, array $data)
    {
        $loan->update($data);
        Cache::forget(self::LOANS_CACHE_KEY);
        return $loan;
    }

    public function deleteLoan(string $loan)
    {
        $loan = Loan::findOrFail($loan);
        $loan->delete();
        Cache::forget(self::LOANS_CACHE_KEY);
        return true;
    }
} 