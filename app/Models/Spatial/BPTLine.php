<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class BPTLine extends Model
{
    protected $table = 'spatial_bpt_line';
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