<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik3Goronggorong extends Model
{
    protected $table = 'data_jalan_teknik3_goronggorong';

    protected $fillable = [
        'tahun',
        'uraian',
        'nilai_ke1',
        'nilai_ke2',
        'nilai_ke3',
        'nilai_ke4',
    ];
}
