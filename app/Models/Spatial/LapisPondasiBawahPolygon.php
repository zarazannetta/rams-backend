<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class LapisPondasiBawahPolygon extends Model
{
    protected $table = 'spatial_lapis_pondasi_bawah_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'tebal',
        'jenis',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}