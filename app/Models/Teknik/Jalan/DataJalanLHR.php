<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanLHR extends Model
{
    protected $table = 'data_jalan_lhr';

    protected $fillable = [
        'tahun',
        'uraian',
        'lhr_ki',
        'lhr_ka',
        'tarif',
        'id_leger_jalan',
    ];

    public function legerJalan()
    {
        return $this->belongsTo(\App\Models\Teknik\Jalan\LegerJalan::class, 'id_leger_jalan');
    }
}
