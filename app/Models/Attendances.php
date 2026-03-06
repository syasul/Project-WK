<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendances extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'user_id', 'leader_id', 'project_id',
        'clock_in_time', 'clock_out_time',
        'late_minutes', 'early_leave_minutes', 'overtime_minutes',
        'status_attendance',
        'latitude', 'longitude'
    ];

    protected $casts = [
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function leader() { return $this->belongsTo(User::class, 'leader_id', 'user_id'); }
    public function project() { return $this->belongsTo(Projects::class, 'project_id'); }
}
