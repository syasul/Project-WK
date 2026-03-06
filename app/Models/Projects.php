<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'project_code', 'name', 'client_name', 'description',
        'address', 'location_id',
        'project_value', 'payment_status', 'status',
        'start_date', 'end_date'
    ];

    protected $casts = [
        'project_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Relasi ke Model Locations
     * Ini wajib ada karena Controller memanggil: Projects::with('location')
     */
    public function location()
    {
        // Parameter: (Model Tujuan, Foreign Key di tabel ini, Primary Key di tabel tujuan)
        return $this->belongsTo(Locations::class, 'location_id', 'location_id');
    }
}