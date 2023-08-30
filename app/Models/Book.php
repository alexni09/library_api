<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\BookRating;

class Book extends Model {
    use HasFactory;

    protected $fillable = ['name','rating','category_id'];

    protected $casts = [
        'rating' => BookRating::class
    ];

    /* Relationships */
    public function category() { return $this->belongsTo(Category::class); }
}