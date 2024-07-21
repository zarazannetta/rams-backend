<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class LegerDetailData extends Model
{
    protected $table = 'leger_detail_data';

    protected $fillable = [
        'leger_identifikasi_id',
        'detail_data_id',
        'jenis_leger',
    ];
}
