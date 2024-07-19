<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class LHRPolygon extends Model
{
    protected $table = 'spatial_lhr_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'segmen_tol',
        'nama_segmen',
        'gol_i',
        'gol_ii',
        'gol_iii',
        'gol_iv',
        'gol_v',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}