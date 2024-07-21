<?php

namespace App\Models\Teknik\Kantor;

use Illuminate\Database\Eloquent\Model;

class LegerKantor extends Model
{
    protected $table = 'leger_kantor';

    protected $fillable = [
        'leger_id',
        'kode_leger',
        'data_kantor_identifikasi_id',
        'data_kantor_luas_lahan_id',
        'data_kantor_teknik1_id',
        'data_kantor_teknik2_id',
        'data_kantor_realisasi_id',
    ];

    public function leger()
    {
        return $this->hasOne(\App\Models\Leger::class, 'id', 'leger_id');
    }

    public function dataKantorIdentifikasi()
    {
        return $this->belongsTo(DataKantorIdentifikasi::class, 'data_kantor_identifikasi_id');
    }

    public function dataKantorLuasLahan()
    {
        return $this->belongsTo(DataKantorLuasLahan::class, 'data_kantor_luas_lahan_id');
    }

    public function dataKantorTeknik1()
    {
        return $this->belongsTo(DataKantorTeknik1::class, 'data_kantor_teknik1_id');
    }

    public function dataKantorTeknik2()
    {
        return $this->belongsTo(DataKantorTeknik2::class, 'data_kantor_teknik2_id');
    }

    public function dataKantorRealisasi()
    {
        return $this->belongsTo(DataKantorRealisasi::class, 'data_kantor_realisasi_id');
    }
}
