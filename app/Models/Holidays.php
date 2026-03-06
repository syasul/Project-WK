<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holidays extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'holiday_id';

    protected $fillable = ['name', 'holiday_date', 'type', 'description'];

    protected $casts = [
        'holiday_date' => 'date',
    ];
}
