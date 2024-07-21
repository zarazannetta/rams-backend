<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class DataJalanLainnya extends Model
{
    protected $table = 'data_jalan_lainnya';

    protected $fillable = [
        'tahun',
        'uraian',
        'tanggal_pemanfaatan',
        'nilai',
    ];
}
