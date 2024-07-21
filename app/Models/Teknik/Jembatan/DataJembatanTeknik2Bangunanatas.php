<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanTeknik2Bangunanatas extends Model
{
    protected $table = 'data_jembatan_teknik2_bangunanatas';

    protected $fillable = [
        'jenis_konstruksi_id',
        'tipe',
        'uraian',
        'bentang1',
        'bentang2',
        'bentang3',
        'bentang4',
    ];

    public function jenisKonstruksi()
    {
        return $this->belongsTo(\App\Models\Teknik\JenisKonstruksi::class, 'jenis_konstruksi_id');
    }
}
