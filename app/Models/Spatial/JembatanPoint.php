<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class JembatanPoint extends Model
{
    protected $table = 'spatial_jembatan_point';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'nama',
        'km',
        'panjang',
        'lebar',
        'luas',
        'absis_x',
        'ordinat_y',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}