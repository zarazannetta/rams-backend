<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanTeknik3Bangunanpengaman extends Model
{
    protected $table = 'data_jembatan_teknik3_bangunanpengaman';

    protected $fillable = [
        'macam',
        'bahan_pas_batu',
        'bahan_pas_bata',
        'bahan_kayu',
        'bahan_baja',
        'bahan_beton',
        'bahan_batu_belah',
        'bahan_bata',
        'kondisi_berkarat',
        'kondisi_bergetar',
        'kondisi_retak_biasa',
        'kondisi_berlubang',
        'kondisi_retak_kritis',
        'kondisi_tergerus',
        'kondisi_bergeser',
        'kondisi_patah',
    ];
}
