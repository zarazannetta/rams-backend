<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class LegerJembatan extends Model
{
    protected $table = 'leger_identifikasi_jembatan';

    protected $fillable = [
        'jalan_tol_id',
        'leger_id',
        'kode_provinsi_id',
        'kode_kabkot_id',
        'kode_kecamatan_id',
        'kode_desakel_id',
        'nomor_ruas',
        'nomor_seksi',
        'deskripsi_seksi',
        'nama_ruas',
        'nama_jembatan',
        'panjang_jembatan',
        'luas_jembatan',
        'kelas_jembatan',
        'lokasi',
        'titik_ikat_leger_kode',
        'titik_ikat_leger_x',
        'titik_ikat_leger_y',
        'titik_ikat_leger_z',
        'titik_ikat_leger_deskripsi',
        'tanggal_selesai_bangun',
        'tanggal_dibuka',
        'tanggal_ditutup',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }
}
