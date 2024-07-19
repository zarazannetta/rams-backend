<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class BatasDesaLine extends Model
{
    protected $table = 'spatial_batas_desa_line';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'layer',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}