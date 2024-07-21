<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik5Utilitas extends Model
{
    protected $table = 'data_jalan_teknik5_utilitas';

    protected $fillable = [
        'jenis_sarana_id',
        'tahun',
        'uraian',
        'nilai_ki',
        'nilai_md',
        'nilai_ka',
    ];

    public function jenisSarana()
    {
        return $this->belongsTo(\App\Models\Teknik\JenisSarana::class, 'jenis_sarana_id');
    }
}
