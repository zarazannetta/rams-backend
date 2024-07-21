<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanTeknik1Bangunanatas extends Model
{
    protected $table = 'data_jembatan_teknik1_bangunanatas';

    protected $fillable = [
        'tipe',
        'bentang1',
        'bentang2',
        'bentang3',
        'bentang4',
    ];
}
