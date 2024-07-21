<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanTeknik1Bangunanbawah extends Model
{
    protected $table = 'data_jembatan_teknik1_bangunanbawah';

    protected $fillable = [
        'tipe',
        'kep_jbt_ki',
        'pilar1',
        'pilar2',
        'pilar3',
        'kep_jbt_ka',
    ];
}
