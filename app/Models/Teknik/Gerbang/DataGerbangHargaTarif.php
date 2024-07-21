<?php

namespace App\Models\Teknik\Gerbang;

use Illuminate\Database\Eloquent\Model;

class DataGerbangHargaTarif extends Model
{
    protected $table = 'data_gerbang_harga_tarif';

    protected $fillable = [
        'tahun',
        'gerbang',
        'gol_1',
        'gol_2',
        'gol_3',
        'gol_4',
        'gol_5',
        'gol_6',
    ];
}
