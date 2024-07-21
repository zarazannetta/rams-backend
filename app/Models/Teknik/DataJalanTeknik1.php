<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik1 extends Model
{
    protected $table = 'data_jalan_teknik1';

    protected $fillable = [
        'tahun',
        'uraian',
        'luas',
        'data_perolehan',
        'nilai_perolehan',
        'bukti_perolehan',
    ];
}
