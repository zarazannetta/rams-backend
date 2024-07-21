<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik3Saluran extends Model
{
    protected $table = 'data_jalan_teknik3_saluran';

    protected $fillable = [
        'jenis_saluran_id',
        'tahun',
        'uraian',
        'nilai_ke1_ki',
        'nilai_ke1_md',
        'nilai_ke1_ka',
        'nilai_ke2_ki',
        'nilai_ke2_md',
        'nilai_ke2_ka',
        'nilai_ke3_ki',
        'nilai_ke3_md',
        'nilai_ke3_ka',
        'nilai_ke4_ki',
        'nilai_ke4_md',
        'nilai_ke4_ka',
    ];

    public function jenisSaluran()
    {
        return $this->belongsTo(\App\Models\Teknik\JenisSaluran::class, 'jenis_saluran_id');
    }
}
