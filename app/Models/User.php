<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'shift_id', 'name', 'email', 'password', 
        'phone', 'position', 'role', 'status', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function shift() { return $this->belongsTo(Shifts::class, 'shift_id'); }


    public function managedLocations()
    {
        // User (Leader) bisa memegang banyak lokasi (atau satu)
        // Parameter: (Model Tujuan, Foreign Key di tujuan, Local Key di sini)
        return $this->hasMany(Locations::class, 'leader_id', 'user_id');
    }
}