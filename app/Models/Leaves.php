<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leaves extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'leave_id';

    protected $fillable = [
        'user_id', 'type', 'start_date', 'end_date',
        'reason', 'attachment', 'status', 'admin_note'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
}
