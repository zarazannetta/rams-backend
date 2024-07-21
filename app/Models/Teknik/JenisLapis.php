<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class JenisLapis extends Model
{
    protected $table = 'reff_jenis_lapis';

    protected $fillable = [
        'jenis',
    ];
}
