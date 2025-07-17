<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

class Loan extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'id',
        'user_id',
        'book_id',
        'devolution_date',
        'status',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'book_id' => 'string',
        'devolution_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
} 