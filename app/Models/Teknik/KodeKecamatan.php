<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class KodeKecamatan extends Model
{
    protected $table = 'reff_kode_kecamatan';

    protected $fillable = [
        'kode',
        'nama',
    ];
}
