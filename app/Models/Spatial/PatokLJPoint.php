<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class PatokLJPoint extends Model
{
    protected $table = 'spatial_patok_lj_point';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'layer',
        'keterangan',
        'deskripsi',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}