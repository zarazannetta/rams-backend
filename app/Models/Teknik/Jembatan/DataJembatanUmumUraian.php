<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanUmumUraian extends Model
{
    protected $table = 'data_jembatan_umum_uraian';

    protected $fillable = [
        'tahun',
        'uraian',
        'nilai',
    ];
}
