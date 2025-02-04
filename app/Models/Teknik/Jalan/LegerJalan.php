<?php

namespace App\Models\Teknik\Jalan;

use Illuminate\Database\Eloquent\Model;

class LegerJalan extends Model
{
    protected $table = 'leger_jalan';

    protected $fillable = [
        'leger_id',
        'kode_leger'
    ];

    public function leger()
    {
        return $this->hasOne(\App\Models\Leger::class, 'id', 'leger_id');
    }
}
