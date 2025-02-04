<?php

namespace App\Models\Teknik\Jalan;

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
        'id_leger_jalan',
    ];

    public function legerJalan()
    {
        return $this->belongsTo(\App\Models\Teknik\Jalan\LegerJalan::class, 'id_leger_jalan');
    }
}
