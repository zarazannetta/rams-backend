<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class LampuLalulintasPoint extends Model
{
    protected $table = 'spatial_lampu_lalulintas_point';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'absis_x',
        'ordinat_y',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}
