<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class JenisKonstruksi extends Model
{
    protected $table = 'reff_jenis_konstruksi';

    protected $fillable = [
        'jenis',
    ];
}
