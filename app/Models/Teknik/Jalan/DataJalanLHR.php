<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanLHR extends Model
{
    protected $table = 'data_jalan_lhr';

    protected $fillable = [
        'tahun',
        'uraian',
        'lhr_ki',
        'lhr_ka',
        'tarif_ki',
        'tarif_ka',
    ];
}
