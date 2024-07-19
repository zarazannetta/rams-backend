<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class SegmenPerlengkapanPolygon extends Model
{
    protected $table = 'spatial_segmen_perlengkapan_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'jalur',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}