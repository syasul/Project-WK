<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shifts extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'shift_id';

    protected $fillable = ['name', 'start_time', 'end_time'];
}
