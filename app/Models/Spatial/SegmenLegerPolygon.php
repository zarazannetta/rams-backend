<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class SegmenLegerPolygon extends Model
{
    protected $table = 'spatial_segmen_leger_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'id_leger',
        'km',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}