<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class SaluranLine extends Model
{
    protected $table = 'spatial_saluran_line';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'layer',
        'jenis_material',
        'kondisi',
        'panjang',
        'lebar',
        'tinggi',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}