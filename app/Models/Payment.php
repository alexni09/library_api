<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Payment extends Model {
    protected $fillable = ['exemplar_id','user_id','due_value','due_from','due_at','paid_at'];
    public $timestamps = false;

    /* Relationships */
    public function exemplar() { return $this->belongsTo(Exemplar::class); }
    public function user() { return $this->belongsTo(User::class); }

    /* Misc */
    public static function hasAnOpenPaymentBeforeDueDate(int $exemplar_id):bool {
        return (bool) DB::table('payments')
            ->selectRaw('count(distinct id) as qty')
            ->whereNull('paid_at')
            ->where('exemplar_id',$exemplar_id)
            ->where('due_at', '>=', Carbon::now())->get()[0]->qty;
    }

    public static function hasOpenPayments(int $user_id):bool {
        return (bool) DB::table('payments')
            ->selectRaw('count(distinct id) as qty')
            ->whereNull('paid_at')
            ->where('user_id',$user_id)
            ->where('due_at', '<=', Carbon::now())->get()[0]->qty;
    }
}