<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik2Median extends Model
{
    protected $table = 'data_jalan_teknik2_median';

    protected $fillable = [
        'tahun',
        'uraian',
        'nilai',
    ];
}
