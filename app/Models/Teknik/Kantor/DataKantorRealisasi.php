<?php

namespace App\Models\Teknik\Kantor;

use Illuminate\Database\Eloquent\Model;

class DataKantorRealisasi extends Model
{
    protected $table = 'data_kantor_realisasi';

    protected $fillable = [
        'tahun',
        'kegiatan',
        'penyedia_jasa',
        'cacah',
        'biaya',
        'sumber_dana',
    ];
}
