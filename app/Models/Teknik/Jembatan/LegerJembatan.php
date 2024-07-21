<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class LegerJembatan extends Model
{
    protected $table = 'leger_jembatan';

    protected $fillable = [
        'leger_id',
        'kode_leger',
        'data_jembatan_identifikasi_id',
        'data_jembatan_umum_uraian_id',
        'data_jembatan_umum_elevasi_id',
        'data_jembatan_teknik1_bangunanatas_id',
        'data_jembatan_teknik1_bangunanbawah_id',
        'data_jembatan_teknik1_pondasi_id',
        'data_jembatan_teknik2_bangunanatas_id',
        'data_jembatan_teknik2_bangunanbawah_id',
        'data_jembatan_teknik2_pondasi_id',
        'data_jembatan_teknik3_landasan_id',
        'data_jembatan_teknik3_bangunanpengaman_id',
        'data_jembatan_kondisi_umum_id',
        'data_jembatan_realisasi_id',
    ];

    public function leger()
    {
        return $this->hasOne(\App\Models\Leger::class, 'id', 'leger_id');
    }

    public function dataJembatanIdentifikasi()
    {
        return $this->belongsTo(DataJembatanIdentifikasi::class, 'data_jembatan_identifikasi_id');
    }

    public function dataJembatanUmumUraian()
    {
        return $this->belongsTo(DataJembatanUmumUraian::class, 'data_jembatan_umum_uraian_id');
    }

    public function dataJembatanUmumElevasi()
    {
        return $this->belongsTo(DataJembatanUmumElevasi::class, 'data_jembatan_umum_elevasi_id');
    }

    public function dataJembatanTeknik1Bangunanatas()
    {
        return $this->belongsTo(DataJembatanTeknik1Bangunanatas::class, 'data_jembatan_teknik1_bangunanatas_id');
    }

    public function dataJembatanTeknik1Bangunanbawah()
    {
        return $this->belongsTo(DataJembatanTeknik1Bangunanbawah::class, 'data_jembatan_teknik1_bangunanbawah_id');
    }

    public function dataJembatanTeknik1Pondasi()
    {
        return $this->belongsTo(DataJembatanTeknik1Pondasi::class, 'data_jembatan_teknik1_pondasi_id');
    }

    public function dataJembatanTeknik2Bangunanatas()
    {
        return $this->belongsTo(DataJembatanTeknik2Bangunanatas::class, 'data_jembatan_teknik2_bangunanatas_id');
    }

    public function dataJembatanTeknik2Bangunanbawah()
    {
        return $this->belongsTo(DataJembatanTeknik2Bangunanbawah::class, 'data_jembatan_teknik2_bangunanbawah_id');
    }

    public function dataJembatanTeknik2Pondasi()
    {
        return $this->belongsTo(DataJembatanTeknik2Pondasi::class, 'data_jembatan_teknik2_pondasi_id');
    }

    public function dataJembatanTeknik3Landasan()
    {
        return $this->belongsTo(DataJembatanTeknik3Landasan::class, 'data_jembatan_teknik3_landasan_id');
    }

    public function dataJembatanTeknik3Bangunanpengaman()
    {
        return $this->belongsTo(DataJembatanTeknik3Bangunanpengaman::class, 'data_jembatan_teknik3_bangunanpengaman_id');
    }

    public function dataJembatanKondisiUmum()
    {
        return $this->belongsTo(DataJembatanKondisiUmum::class, 'data_jembatan_kondisi_umum_id');
    }

    public function dataJembatanRealisasi()
    {
        return $this->belongsTo(DataJembatanRealisasi::class, 'data_jembatan_realisasi_id');
    }
}
