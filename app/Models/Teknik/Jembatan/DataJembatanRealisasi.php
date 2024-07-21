<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanRealisasi extends Model
{
    protected $table = 'data_jembatan_realisasi';

    protected $fillable = [
        'kegiatan_pokok',
        'tahun',
        'pelaksana',
        'cacah',
        'biaya',
        'sumber_dana',
    ];
}
