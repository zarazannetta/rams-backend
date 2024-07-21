<?php

namespace App\Models\Teknik\Rumija;

use Illuminate\Database\Eloquent\Model;

class DataRumijaKoordinatPatok extends Model
{
    protected $table = 'data_rumija_koordinat_patok';

    protected $fillable = [
        'no_patok',
        'koordinat_x',
        'koordinat_y',
        'koordinat_z',
        'deskripsi',
    ];
}
