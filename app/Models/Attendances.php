<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendances extends Model
{
    use SoftDeletes;

    protected $table = 'attendances';
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'user_id', 
        'leader_id', 
        'project_id',
        'clock_in_time', 
        'clock_out_time',
        'late_minutes', 
        'early_leave_minutes', 
        'overtime_minutes',
        'status_attendance',
        'latitude', 
        'longitude',
        'image_url'
    ];

    protected $casts = [
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
    ];

    public function user(): BelongsTo
    { 
        return $this->belongsTo(User::class, 'user_id', 'user_id'); 
    }

    public function leader(): BelongsTo
    { 
        return $this->belongsTo(User::class, 'leader_id', 'user_id'); 
    }

    public function project(): BelongsTo
    { 
        return $this->belongsTo(Projects::class, 'project_id', 'project_id'); 
    }
}