<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class SegmenTolPolygon extends Model
{
    protected $table = 'spatial_segmen_tol_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'segmen_tol',
        'nama_segmen',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}