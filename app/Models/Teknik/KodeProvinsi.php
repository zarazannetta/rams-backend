<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class KodeProvinsi extends Model
{
    protected $table = 'reff_kode_provinsi';

    protected $fillable = [
        'kode',
        'nama',
    ];
}
