<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class KodeDesakel extends Model
{
    protected $table = 'reff_kode_desakel';

    protected $fillable = [
        'kode',
        'nama',
    ];
}
