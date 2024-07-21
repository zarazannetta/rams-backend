<?php

namespace App\Models\Teknik\Gerbang;

use Illuminate\Database\Eloquent\Model;

class LegerGerbang extends Model
{
    protected $table = 'leger_gerbang';

    protected $fillable = [
        'leger_id',
        'kode_leger',
        'data_gerbang_identifikasi_id',
        'data_gerbang_teknik1_id',
        'data_gerbang_luas_lahan_id',
        'data_gerbang_teknik2_id',
        'data_gerbang_harga_tarif_id',
        'data_gerbang_realisasi_id',
    ];

    public function leger()
    {
        return $this->hasOne(\App\Models\Leger::class, 'id', 'leger_id');
    }

    public function dataGerbangIdentifikasi()
    {
        return $this->belongsTo(DataGerbangIdentifikasi::class, 'data_gerbang_identifikasi_id');
    }

    public function dataGerbangTeknik1()
    {
        return $this->belongsTo(DataGerbangTeknik1::class, 'data_gerbang_teknik1_id');
    }

    public function dataGerbangLuasLahan()
    {
        return $this->belongsTo(DataGerbangLuasLahan::class, 'data_gerbang_luas_lahan_id');
    }

    public function dataGerbangTeknik2()
    {
        return $this->belongsTo(DataGerbangTeknik2::class, 'data_gerbang_teknik2_id');
    }

    public function dataGerbangHargaTarif()
    {
        return $this->belongsTo(DataGerbangHargaTarif::class, 'data_gerbang_harga_tarif_id');
    }

    public function dataGerbangRealisasi()
    {
        return $this->belongsTo(DataGerbangRealisasi::class, 'data_gerbang_realisasi_id');
    }
}
