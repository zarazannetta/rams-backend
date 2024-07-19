<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class RuwasjaPolygon extends Model
{
    protected $table = 'spatial_ruwasja_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'keterangan',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}