<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class AdministratifPolygon extends Model
{
    protected $table = 'spatial_administratif_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'txtmemo',
        'kode_prov',
        'nama_prov',
        'kode_kab',
        'nama_kab',
        'kode_kec',
        'nama_kec',
        'kode_desa',
        'nama_desa',
        'tahun',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}