<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik3Bangunan extends Model
{
    protected $table = 'data_jalan_teknik3_bangunan';

    protected $fillable = [
        'jenis_bangunan_id',
        'tahun',
        'uraian',
        'nilai_ke1_ki',
        'nilai_ke1_ka',
        'nilai_ke2_ki',
        'nilai_ke2_ka',
        'nilai_ke3_ki',
        'nilai_ke3_ka',
        'nilai_ke4_ki',
        'nilai_ke4_ka',
    ];

    public function jenisBangunan()
    {
        return $this->belongsTo(\App\Models\Teknik\JenisBangunan::class, 'jenis_bangunan_id');
    }
}
