<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class JenisSaluran extends Model
{
    protected $table = 'reff_jenis_saluran';

    protected $fillable = [
        'jenis',
    ];

    public function dataJalanTeknik3Saluran()
    {
        return $this->hasMany(\App\Models\Teknik\Jalan\DataJalanTeknik3Saluran::class, 'jenis_sa_id', 'id');
    }
}
