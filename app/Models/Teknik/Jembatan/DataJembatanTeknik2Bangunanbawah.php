<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanTeknik2Bangunanbawah extends Model
{
    protected $table = 'data_jembatan_teknik2_bangunanbawah';

    protected $fillable = [
        'tipe',
        'uraian',
        'kep_jbt_ki',
        'pilar1',
        'pilar2',
        'pilar3',
        'kep_jbt_ka',
    ];
}
