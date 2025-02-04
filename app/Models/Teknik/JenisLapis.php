<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class JenisLapis extends Model
{
    protected $table = 'reff_jenis_lapis';

    protected $fillable = [
        'jenis',
    ];

    public function dataJalanTeknik2BahuJalan()
    {
        return $this->hasMany(\App\Models\Teknik\Jalan\DataJalanTeknik2BahuJalan::class, 'jenis_lapis_id', 'id');
    }
    
    public function dataJalanTeknik2Lapis()
    {
        return $this->hasMany(\App\Models\Teknik\Jalan\DataJalanTeknik2Lapis::class, 'jenis_lapis_id', 'id');
    }
}
