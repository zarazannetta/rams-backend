<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik5Bangunan extends Model
{
    protected $table = 'data_jalan_teknik5_bangunan';

    protected $fillable = [
        'tahun',
        'uraian',
        'luas_lahan',
        'luas_bangunan',
        'nilai_lahan',
        'nilai_bangunan',
    ];
}
