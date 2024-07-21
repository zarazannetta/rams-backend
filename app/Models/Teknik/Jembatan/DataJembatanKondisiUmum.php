<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanKondisiUmum extends Model
{
    protected $table = 'data_jembatan_kondisi_umum';

    protected $fillable = [
        'uraian',
        'bangunan_atas_bentang1',
        'bangunan_atas_bentang2',
        'bangunan_atas_bentang3',
        'bangunan_atas_bentang4',
        'bangunan_bawah_kep_jbt_ki',
        'bangunan_bawah_pilar1',
        'bangunan_bawah_pilar2',
        'bangunan_bawah_pilar3',
        'bangunan_bawah_kep_jbt_ka',
        'pondasi_kep_jbt_ki',
        'pondasi_pilar1',
        'pondasi_pilar2',
        'pondasi_pilar3',
        'pondasi_kep_jbt_ka',
    ];
}
