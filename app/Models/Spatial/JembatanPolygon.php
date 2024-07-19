<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class JembatanPolygon extends Model
{
    protected $table = 'spatial_jembatan_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'nama',
        'km',
        'panjang',
        'lebar',
        'luas',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}