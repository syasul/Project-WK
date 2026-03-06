<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locations extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'location_id';

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'radius',
        'address',
        'leader_id',
    ];

    // Relasi ke User (Leader)
    public function leader()
    {
        // Lokasi dimiliki oleh satu User (Leader)
        return $this->belongsTo(User::class, 'leader_id', 'user_id');
    }
}