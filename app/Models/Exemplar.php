<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\ExemplarCondition;
use Illuminate\Support\Facades\DB;

class Exemplar extends Model {
    use HasFactory;

    protected $fillable = ['borrowable','condition','book_id','user_id'];

    protected $casts = [
        'condition' => ExemplarCondition::class
    ];

    /* Relationships */
    public function book() { return $this->belongsTo(Book::class); }
    public function donor() { return $this->belongsTo(User::class); }
    public function borrowed() { return $this->belongsToMany(User::class)->withPivot('borrowed', 'returned'); }
    public function unreturned() { return $this->belongsToMany(User::class)->as('unreturned')->wherePivotNull('returned'); }

    /* Misc */
    public function borrowedDate() {
        $e = DB::table('exemplar_user')->where('exemplar_id',$this->id)->whereNull('returned')->get();
        if ($e->isEmpty()) return null;
        else return $e[0]->borrowed;
    }
}