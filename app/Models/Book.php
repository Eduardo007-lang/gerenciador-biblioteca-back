<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Book extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'id',
        'name',
        'author',
        'registration_number',
        'status',
    ];

    protected $casts = [
        'id' => 'string',
        'registration_number' => 'integer',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre', 'book_id', 'genre_id');
    }
} 