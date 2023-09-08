<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\ExemplarCondition;

class Exemplar extends Model {
    use HasFactory;

    protected $fillable = ['borrowable','condition','book_id','user_id'];

    protected $casts = [
        'condition' => ExemplarCondition::class
    ];

    /* Relationships */
    public function book() { return $this->belongsTo(Book::class); }
    public function donor() { return $this->belongsTo(User::class); }
    public function borrowed() { return $this->belongsToMany(User::class); }
}