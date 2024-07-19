<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class PatokHMPoint extends Model
{
    protected $table = 'spatial_patok_hm_point';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'layer',
        'km',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}