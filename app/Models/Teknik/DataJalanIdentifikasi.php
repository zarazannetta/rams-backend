<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class DataJalanIdentifikasi extends Model
{
    protected $table = 'data_jalan_identifikasi';

    protected $fillable = [
        'kode_provinsi_id',
        'kode_kabkot_id',
        'kode_kecamatan_id',
        'kode_desakel_id',
        'nomor_ruas',
        'nomor_seksi',
        'deskripsi_seksi',
        'lokasi',
        'titik_ikat_leger_kode',
        'titik_ikat_leger_x',
        'titik_ikat_leger_y',
        'titik_ikat_leger_z',
        'titik_ikat_leger_deskripsi',
        'titik_ikat_patok_kode',
        'titik_ikat_patok_km',
        'titik_ikat_patok_x',
        'titik_ikat_patok_y',
        'titik_ikat_patok_z',
        'titik_ikat_patok_deskripsi',
        'titik_awal_segmen_kode',
        'titik_awal_segmen_km',
        'titik_awal_segmen_x',
        'titik_awal_segmen_y',
        'titik_awal_segmen_z',
        'titik_awal_segmen_deskripsi',
        'titik_akhir_segmen_kode',
        'titik_akhir_segmen_km',
        'titik_akhir_segmen_x',
        'titik_akhir_segmen_y',
        'titik_akhir_segmen_z',
        'titik_akhir_segmen_deskripsi',
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
