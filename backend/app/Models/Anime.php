<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'genres',
        'score',
        'image_url',
        'synopsis',
    ];

    protected function casts(): array
    {
        return [
            'genres' => 'array',
            'score' => 'decimal:2',
        ];
    }
}
