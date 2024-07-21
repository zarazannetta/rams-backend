<?php

namespace App\Models\Teknik\Kantor;

use Illuminate\Database\Eloquent\Model;

class DataKantorTeknik1 extends Model
{
    protected $table = 'data_kantor_teknik1';

    protected $fillable = [
        'tahun',
        'uraian',
        'jumlah',
        'luas_lahan',
        'luas_bangunan',
        'kondisi',
    ];
}
