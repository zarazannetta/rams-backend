<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik2Bahujalan extends Model
{
    protected $table = 'data_jalan_teknik2_bahujalan';

    protected $fillable = [
        'tahun',
        'uraian',
        'nilai_ki_dalam',
        'nilai_ki_luar',
        'nilai_ka_dalam',
        'nilai_ka_luar',
        'jenis_lapis_id',
        'id_leger_jalan'
    ];

    public function legerJalan()
    {
        return $this->belongsTo(\App\Models\Teknik\Jalan\LegerJalan::class, 'id_leger_jalan');
    }

    public function jenisLapis()
    {
        return $this->belongsTo(\App\Models\Teknik\JenisLapis::class, 'jenis_lapis_id');
    }
}
