<?php

namespace App\Models\Spatial;

use Illuminate\Database\Eloquent\Model;

class SegmenSeksiPolygon extends Model
{
    protected $table = 'spatial_segmen_seksi_polygon';
    protected $fillable = [
        'jalan_tol_id',
        'geom',
        'no_ruas',
        'nama_ruas',
        'seksi',
        'keterangan',
        'km_awal',
        'km_akhir',
        'sta_awal',
        'sta_akhir',
        'x_awal',
        'x_akhir',
        'y_awal',
        'y_akhir',
        'z_awal',
        'z_akhir',
        'deskripsi_awal',
        'deskripsi_akhir',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}