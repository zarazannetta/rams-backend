<?php

namespace App\Models\Teknik\Kantor;

use Illuminate\Database\Eloquent\Model;

class DataKantorLuasLahan extends Model
{
    protected $table = 'data_kantor_luas_lahan';

    protected $fillable = [
        'tahun',
        'luas',
        'data_perolehan',
        'nilai_perolehan',
        'bukti_perolehan',
    ];
}
