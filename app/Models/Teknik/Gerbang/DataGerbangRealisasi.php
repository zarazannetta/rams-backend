<?php

namespace App\Models\Teknik\Gerbang;

use Illuminate\Database\Eloquent\Model;

class DataGerbangRealisasi extends Model
{
    protected $table = 'data_gerbang_realisasi';

    protected $fillable = [
        'tahun',
        'kegiatan',
        'penyedia_jasa',
        'cacah',
        'biaya',
        'sumber_dana',
    ];
}
