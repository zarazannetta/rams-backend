<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class DataJembatanIdentifikasi extends Model
{
    protected $table = 'data_jembatan_identifikasi';

    protected $fillable = [
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

    public function kodeProvinsi()
    {
        return $this->belongsTo(KodeProvinsi::class, 'kode_provinsi_id');
    }

    public function kodeKabkot()
    {
        return $this->belongsTo(KodeKabkot::class, 'kode_kabkot_id');
    }

    public function kodeKecamatan()
    {
        return $this->belongsTo(KodeKecamatan::class, 'kode_kecamatan_id');
    }

    public function kodeDesakel()
    {
        return $this->belongsTo(KodeDesakel::class, 'kode_desakel_id');
    }
}
