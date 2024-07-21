<?php

namespace App\Models\Teknik\Jembatan;

use Illuminate\Database\Eloquent\Model;

class DataJembatanUmumElevasi extends Model
{
    protected $table = 'data_jembatan_umum_elevasi';

    protected $fillable = [
        'jenis_elevasi',
        'tahun',
        'uraian',
        'nilai_hulu',
        'nilai_hilir',
    ];
}
