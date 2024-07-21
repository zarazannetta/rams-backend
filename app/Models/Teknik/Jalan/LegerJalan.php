<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class LegerJalan extends Model
{
    protected $table = 'leger_jalan';

    protected $fillable = [
        'leger_id',
        'kode_leger',
        'data_jalan_identifikasi_id',
        'data_jalan_teknik1_id',
        'data_jalan_teknik2_lapis_id',
        'data_jalan_teknik2_median_id',
        'data_jalan_teknik2_bahujalan_id',
        'data_jalan_teknik3_goronggorong_id',
        'data_jalan_teknik3_saluran_id',
        'data_jalan_teknik3_bangunan_id',
        'data_jalan_teknik4_id',
        'data_jalan_teknik5_utilitas_id',
        'data_jalan_teknik5_bangunan_id',
        'data_jalan_lhr_id',
        'data_jalan_geometrik_id',
        'data_jalan_lingkungan_id',
        'data_jalan_lainnya_id',
    ];

    public function leger()
    {
        return $this->hasOne(\App\Models\Leger::class, 'id', 'leger_id');
    }

    public function dataJalanIdentifikasi()
    {
        return $this->belongsTo(DataJalanIdentifikasi::class, 'data_jalan_identifikasi_id');
    }

    public function dataJalanTeknik1()
    {
        return $this->belongsTo(DataJalanTeknik1::class, 'data_jalan_teknik1_id');
    }

    public function dataJalanTeknik2Lapis()
    {
        return $this->belongsTo(DataJalanTeknik2Lapis::class, 'data_jalan_teknik2_lapis_id');
    }

    public function dataJalanTeknik2Median()
    {
        return $this->belongsTo(DataJalanTeknik2Median::class, 'data_jalan_teknik2_median_id');
    }

    public function dataJalanTeknik2BahuJalan()
    {
        return $this->belongsTo(DataJalanTeknik2Bahujalan::class, 'data_jalan_teknik2_bahujalan_id');
    }

    public function dataJalanTeknik3Goronggorong()
    {
        return $this->belongsTo(DataJalanTeknik3Goronggorong::class, 'data_jalan_teknik3_goronggorong_id');
    }

    public function dataJalanTeknik3Saluran()
    {
        return $this->belongsTo(DataJalanTeknik3Saluran::class, 'data_jalan_teknik3_saluran_id');
    }

    public function dataJalanTeknik3Bangunan()
    {
        return $this->belongsTo(DataJalanTeknik3Bangunan::class, 'data_jalan_teknik3_bangunan_id');
    }

    public function dataJalanTeknik4()
    {
        return $this->belongsTo(DataJalanTeknik4::class, 'data_jalan_teknik4_id');
    }

    public function dataJalanTeknik5Utilitas()
    {
        return $this->belongsTo(DataJalanTeknik5Utilitas::class, 'data_jalan_teknik5_utilitas_id');
    }

    public function dataJalanTeknik5Bangunan()
    {
        return $this->belongsTo(DataJalanTeknik5Bangunan::class, 'data_jalan_teknik5_bangunan_id');
    }

    public function dataJalanLHR()
    {
        return $this->belongsTo(DataJalanLHR::class, 'data_jalan_lhr_id');
    }

    public function dataJalanGeometrik()
    {
        return $this->belongsTo(DataJalanGeometrik::class, 'data_jalan_geometrik_id');
    }

    public function dataJalanLingkungan()
    {
        return $this->belongsTo(DataJalanLingkungan::class, 'data_jalan_lingkungan_id');
    }

    public function dataJalanLainnya()
    {
        return $this->belongsTo(DataJalanLainnya::class, 'data_jalan_lainnya_id');
    }
}
