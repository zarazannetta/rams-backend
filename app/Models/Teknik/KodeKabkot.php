<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class KodeKabkot extends Model
{
    protected $table = 'reff_kode_kabkot';

    protected $fillable = [
        'kode',
        'nama',
    ];
}
