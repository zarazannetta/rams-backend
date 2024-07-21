<?php

namespace App\Models\Teknik\Rumija;

use Illuminate\Database\Eloquent\Model;

class DataRumijaPembebasanLahan extends Model
{
    protected $table = 'data_rumija_pembebasan_lahan';

    protected $fillable = [
        'desabidang',
        'luasan',
        'sumber_pendanaan',
        'nilai_perolehan',
        'dokumen_perolehan',
        'nomor_dokumen',
        'tanggal_dokumen',
    ];
}
