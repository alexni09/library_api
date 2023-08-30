<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    use HasFactory;

    protected $fillable = ['name','category_id'];

    /* Relationships */
    public function category() { return $this->belongsTo(Category::class); }
}