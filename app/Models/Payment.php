<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model {
    protected $fillable = ['exemplar_id','user_id','due_value','due_from','due_at','paid_at'];
    public $timestamps = false;

    /* Relationships */
    public function exemplar() { return $this->belongsTo(Exemplar::class); }
    public function user() { return $this->belongsTo(User::class); }

    /* Misc */
    public static function hasOpenPayments(int $user_id):bool {
        return (bool) DB::table('payments')->selectRaw('count(distinct id) as qty')->whereNull('paid_at')->where('user_id',$user_id)->where('due_at', '<=', 'now()')->get()[0]->qty;
    }
}