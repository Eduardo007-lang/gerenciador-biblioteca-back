<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookService
{
    const BOOKS_CACHE_KEY = 'all_books_with_genres';

    public function listBooks()
    {
        $page = request('page', 1);
        $cacheKey = self::BOOKS_CACHE_KEY . '_page_' . $page;
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 60 * 60, function () {
            return Book::with('genres:id,genre')->paginate(10);
        });
    }

    public function createBook(array $data)
    {
        $genres = $data['genres'] ?? [];
        unset($data['genres']);
        $book = Book::create($data);
        if (!empty($genres)) {
            $book->genres()->sync($genres);
        }
        Cache::forget(self::BOOKS_CACHE_KEY);
        return $book->load('genres:id,genre');
    }

    public function updateBook(Book $book, array $data)
    {
        $genres = $data['genres'] ?? [];
        unset($data['genres']);
        $book->update($data);
        if (!empty($genres)) {
            $book->genres()->sync($genres);
        }
        Cache::forget(self::BOOKS_CACHE_KEY);
        return $book->load('genres:id,genre');
    }

    public function deleteBook(string $book)
    {
        $book = Book::findOrFail($book);
        $book->delete();
        Cache::forget(self::BOOKS_CACHE_KEY);
        return true;
    }
} 