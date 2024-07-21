<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class JenisSarana extends Model
{
    protected $table = 'reff_jenis_sarana';

    protected $fillable = [
        'jenis',
    ];
}
