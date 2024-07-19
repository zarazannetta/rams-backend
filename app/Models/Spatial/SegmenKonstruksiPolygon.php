<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class SegmenKonstruksiPolygon extends Model
{
    protected $table = 'spatial_segmen_konstruksi_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'bagian_jalan',
        'lebar',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}