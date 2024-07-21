<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class LegerDetail extends Model
{
    protected $table = 'leger_detail';

    protected $fillable = [
        'jalan_tol_id',
        'user_id',
        'leger_id',
        'jenis_leger',
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

    public function jalanTol()
    {
        return $this->belongsTo(\App\Models\JalanTol::class, 'jalan_tol_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
