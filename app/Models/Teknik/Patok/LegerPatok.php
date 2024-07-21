<?php

namespace App\Models\Teknik\Patok;

use Illuminate\Database\Eloquent\Model;

class LegerPatok extends Model
{
    protected $table = 'leger_patok';

    protected $fillable = [
        'leger_id',
        'kode_leger',
        'data_patok_id',
    ];

    public function leger()
    {
        return $this->hasOne(\App\Models\Leger::class, 'id', 'leger_id');
    }

    public function dataPatok()
    {
        return $this->belongsTo(DataPatok::class, 'data_patok_id');
    }
}
