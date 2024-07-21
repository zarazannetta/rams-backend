<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class DataJalanGeometrik extends Model
{
    protected $table = 'data_jalan_geometrik';

    protected $fillable = [
        'tahun',
        'uraian',
        'nilai',
    ];
}
