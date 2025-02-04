<?php

namespace App\Models\Teknik\Jalan;


use Illuminate\Database\Eloquent\Model;

class DataJalanGambar extends Model
{
    protected $table = 'data_jalan_gambar';

    protected $fillable = [
        'tahun',
        'path_alinyemen_horizontal',
        'path_alinyemen_vertikal',
        'path_penampang_melintang',
        'id_leger_jalan',
    ];

    public function legerJalan()
    {
        return $this->belongsTo(\App\Models\Teknik\Jalan\LegerJalan::class, 'id_leger_jalan');
    }
}
