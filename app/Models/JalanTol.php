<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JalanTol extends Model
{
    protected $table = 'jalan_tol';

    protected $fillable = [
        'user_id',
        'nama',
        'kode',
        'tahun',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
