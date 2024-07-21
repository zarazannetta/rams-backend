<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik4 extends Model
{
    protected $table = 'data_jalan_teknik4';

    protected $fillable = [
        'tahun',
        'uraian',
        'nilai_ki',
        'nilai_md',
        'nilai_ka',
    ];
}
