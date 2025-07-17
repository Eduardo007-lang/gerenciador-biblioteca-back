<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Genre extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'id',
        'genre',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_genre', 'genre_id', 'book_id');
    }
} 