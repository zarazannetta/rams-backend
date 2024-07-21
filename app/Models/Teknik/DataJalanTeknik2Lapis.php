<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class DataJalanTeknik2Lapis extends Model
{
    protected $table = 'data_jalan_teknik2_lapis';

    protected $fillable = [
        'jenis_lapis_id',
        'tahun',
        'uraian',
        'nilai_ki_lajur1',
        'nilai_ki_lajur2',
        'nilai_ki_lajur3',
        'nilai_ki_lajur4',
        'nilai_ka_lajur1',
        'nilai_ka_lajur2',
        'nilai_ka_lajur3',
        'nilai_ka_lajur4',
    ];

    public function jenisLapis()
    {
        return $this->belongsTo(JenisLapis::class, 'jenis_lapis_id');
    }
}
