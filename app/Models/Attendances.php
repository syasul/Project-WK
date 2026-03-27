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
        'user_id', 'leader_id', 'project_id',
        'clock_in_time', 'clock_out_time',
        'latitude', 'longitude', 'latitude_out', 'longitude_out',
        'image_url', 'image_out_url',
        'late_minutes', 'early_leave_minutes', 'overtime_minutes',
        'status_attendance'
    ];

    protected $casts = [
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
        'late_minutes' => 'integer',
        'early_leave_minutes' => 'integer',
        'overtime_minutes' => 'integer',
    ];

    public function user(): BelongsTo { 
        return $this->belongsTo(User::class, 'user_id', 'user_id'); 
    }
}