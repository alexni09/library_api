<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\ExemplarCondition;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    public function borrowedTimestamp():string {
        $e = DB::table('exemplar_user')->where('exemplar_id',$this->id)->whereNull('returned')->get();
        if ($e->isEmpty()) return null;
        else return $e[0]->borrowed;
    }

    public function computedDamageFine(int $newCondition):int {
        if (!$this->isCurrentlyBorrowed()) return 0;
        if ($newCondition <= $this->condition->value) return 0;
        else return $this->fine_per_damage * ($newCondition - $this->condition->value);
    }

    public function computedDelayFine():int {
        if (!$this->isCurrentlyBorrowed()) return 0;
        $borrowedTS = new Carbon($this->borrowedTimestamp());
        $returnedTS = Carbon::now();
        $maximumTS = $borrowedTS->addMinutes($this->maximum_minutes);
        if ($returnedTS <= $maximumTS ) return 0;
        else return $this->fine_per_delay + $returnedTS->diffInMinutes($borrowedTS) * $this->fine_per_minute;
    }

    public function isCurrentlyBorrowed():bool {
        return (bool) DB::table('exemplar_user')->selectRaw('count(*) as borrowed')->where('exemplar_id',$this->id)->whereNull('returned')->get()[0]->borrowed;
    }
}