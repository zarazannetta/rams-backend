<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class GorongGorongLine extends Model
{
    protected $table = 'spatial_gorong_gorong_line';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'layer',
        'jenis_material',
        'ukuran_panjang',
        'kondisi',
        'diameter',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}