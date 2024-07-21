<?php

namespace App\Models\Teknik\Rumija;

use Illuminate\Database\Eloquent\Model;

class DataRumija extends Model
{
    protected $table = 'data_rumija';

    protected $fillable = [
        'kode_provinsi_id',
        'kode_kabkot_id',
        'nama_ruas',
        'awal_segmen',
        'akhir_segmen',
        'desakel',
        'luasan_apbn',
        'nilai_perolehan_apbn',
        'luasan_nonapbn',
        'nilai_perolehan_nonapbn',
        'kebutuhan_lahan_ugr',
        'kebutuhan_lahan_nonugr',
        'kebutuhan_lahan_total',
        'pengukuran_lapangan_tol_ugr',
        'pengukuran_lapangan_tol_nonugr',
        'pengukuran_lapangan_tol_total',
        'pengukuran_lapangan_nontol_ugr',
        'pengukuran_lapangan_nontol_nonugr',
        'pengukuran_lapangan_nontol_total',
        'total_luasan',
        'luasan_sertifikat',
        'lembar',
    ];

    public function kodeProvinsi()
    {
        return $this->belongsTo(\App\Models\Teknik\KodeProvinsi::class, 'kode_provinsi_id');
    }

    public function kodeKabkot()
    {
        return $this->belongsTo(\App\Models\Teknik\KodeKabkot::class, 'kode_kabkot_id');
    }
}
