<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class DataGeometrikJalanPolygon extends Model
{
    protected $table = 'spatial_data_geometrik_jalan_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'id_leger',
        'segmen_tol',
        'nama',
        'lebar_rmj',
        'gradien_kiri',
        'gradien_kanan',
        'cross_fall_kiri',
        'cross_fall_kanan',
        'super_elevasi',
        'radius',
        'terrain_kiri',
        'terrain_kanan',
        'tataguna_lahan_kiri',
        'tataguna_lahan_kanan',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}