<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanTeknik3Landasan extends Model
{
    protected $table = 'data_jembatan_teknik3_landasan';

    protected $fillable = [
        'jenis',
        'uraian',
        'kep_jbt_ki',
        'pilar1_ki',
        'pilar1_ka',
        'pilar2_ki',
        'pilar2_ka',
        'pilar3_ki',
        'pilar3_ka',
        'kep_jbt_ka',
    ];
}
