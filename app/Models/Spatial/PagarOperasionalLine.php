<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class PagarOperasionalLine extends Model
{
    protected $table = 'spatial_pagar_operasional_line';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'layer',
        'jenis',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}