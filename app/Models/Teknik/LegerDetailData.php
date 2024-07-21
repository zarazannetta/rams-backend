<?php

namespace App\Models\Teknik;

use Illuminate\Database\Eloquent\Model;

class LegerDetailData extends Model
{
    protected $table = 'leger_detail_data';

    protected $fillable = [
        'user_id',
        'leger_identifikasi_id',
        'detail_data_id',
        'jenis_leger',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
