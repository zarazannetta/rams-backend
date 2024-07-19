<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class GuardrailLine extends Model
{
    protected $table = 'spatial_guardrail_line';
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