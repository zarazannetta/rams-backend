<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class JenisLingkungan extends Model
{
    protected $table = 'reff_jenis_lingkungan';

    protected $fillable = [
        'jenis',
    ];
}
