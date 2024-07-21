<?php

namespace App\Models\Teknik\Kantor;

use Illuminate\Database\Eloquent\Model;

class DataKantorTeknik2 extends Model
{
    protected $table = 'data_kantor_teknik2';

    protected $fillable = [
        'tahun',
        'uraian',
        'jumlah',
        'panjang',
        'kondisi',
    ];
}
