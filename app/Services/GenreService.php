<?php

namespace App\Services;

use App\Models\Genre;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class GenreService
{
    const GENRES_CACHE_KEY = 'all_genres';

    public function listGenres()
    {
        $page = request('page', 1);
        $cacheKey = self::GENRES_CACHE_KEY . '_page_' . $page;
        return Cache::remember($cacheKey, 60 * 60, function () {
            return Genre::paginate(10);
        });
    }

    public function createGenre(array $data)
    {
        
        if (empty($data['genre'])) 
            throw new \InvalidArgumentException('O nome do gênero é obrigatório.'); 
        
        if (Genre::where('genre', $data['genre'])->exists()) 
            throw new \Exception('Já existe um gênero com este nome.'); 
        
        $genre = Genre::create($data);
        Cache::forget(self::GENRES_CACHE_KEY);
        return $genre;
    }

    public function updateGenre(Genre $genre, array $data)
    {
    
        if (isset($data['genre']) && empty($data['genre'])) {
             throw new \InvalidArgumentException('O nome do gênero não pode ser vazio.');
        }
    
        
        if (isset($data['genre']) && Genre::where('genre', $data['genre'])->where('id', '!=', $genre->id)->exists()) {
            throw new \Exception('Já existe outro gênero com este nome.');
        }

        $genre->update($data);
        Cache::forget(self::GENRES_CACHE_KEY);
        return $genre;
    }

    public function deleteGenre(Genre $idGenre)
    {   
        $genre = Genre::findOrFail($idGenre->id);
        $genre->delete();
        Cache::forget(self::GENRES_CACHE_KEY);
        return true;
    }
} 