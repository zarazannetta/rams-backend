<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class JenisSaluran extends Model
{
    protected $table = 'reff_jenis_saluran';

    protected $fillable = [
        'jenis',
    ];
}
