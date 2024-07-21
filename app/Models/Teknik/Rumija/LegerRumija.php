<?php

namespace App\Models\Teknik\Rumija;

use Illuminate\Database\Eloquent\Model;

class LegerRumija extends Model
{
    protected $table = 'leger_rumija';

    protected $fillable = [
        'leger_id',
        'kode_leger',
        'data_rumija_id',
        'data_rumija_koordinat_patok_id',
        'data_rumija_pembebasan_lahan_id',
    ];

    public function leger()
    {
        return $this->hasOne(\App\Models\Leger::class, 'id', 'leger_id');
    }

    public function dataRumija()
    {
        return $this->belongsTo(DataRumija::class, 'data_rumija_id');
    }

    public function dataRumijaKoordinatPatok()
    {
        return $this->belongsTo(DataRumijaKoordinatPatok::class, 'data_rumija_koordinat_patok_id');
    }

    public function dataRumijaPembebasanLahan()
    {
        return $this->belongsTo(DataRumijaPembebasanLahan::class, 'data_rumija_pembebasan_lahan_id');
    }
}
