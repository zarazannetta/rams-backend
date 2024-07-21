<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class JenisBangunan extends Model
{
    protected $table = 'reff_jenis_bangunan';

    protected $fillable = [
        'jenis',
    ];
}
