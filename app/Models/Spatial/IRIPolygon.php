<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class IRIPolygon extends Model
{
    protected $table = 'spatial_iri_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'jalur',
        'bagian_jalan',
        'lebar',
        'segmen_tol',
        'km',
        'nilai_iri',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}