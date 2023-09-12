<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'is_admin',
        'name',
        'email',
        'password',
        'maximum_borrowable'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* Relationships */
    public function exemplarsDonated() { return $this->hasMany(Exemplar::class); }
    public function borrowed() { return $this->belongsToMany(Exemplar::class)->withPivot('borrowed', 'returned'); }
    public function unreturned() { return $this->belongsToMany(Exemplar::class)->wherePivotNull('returned'); }
    public function payments() { return $this->hasMany(Payment::class); }

    /* Misc */
    public function allPaymentsTotal():int { return Payment::allPaymentsTotal($this->id); }
    public function balanceDueOpen():int { return Payment::balanceDueOpen($this->id); }
    public function balanceDueUnpaid():int { return Payment::balanceDueUnpaid($this->id); }
    public function hasOpenPayments():bool { return Payment::hasOpenPayments($this->id); }
}