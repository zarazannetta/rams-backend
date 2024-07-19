<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class ManholePoint extends Model
{
    protected $table = 'spatial_manhole_point';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'layer',
        'jenis_material',
        'ukuran_pokok',
        'kondisi',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}