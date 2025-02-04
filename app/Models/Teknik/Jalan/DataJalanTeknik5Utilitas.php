<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik5Utilitas extends Model
{
    protected $table = 'data_jalan_teknik5_utilitas';

    protected $fillable = [
        'tahun',
        'uraian',
        'nilai_ki',
        'nilai_md',
        'nilai_ka',
        'id_leger_jalan',
    ];

    public function legerJalan()
    {
        return $this->belongsTo(\App\Models\Teknik\Jalan\LegerJalan::class, 'id_leger_jalan');
    }
}
