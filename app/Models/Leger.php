<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leger extends Model
{
    protected $table = 'leger';

    protected $fillable = [
        'jalan_tol_id',
        'user_id',
        'kode_leger',
        'jenis_leger',
    ];

    public function jalanTol()
    {
        return $this->belongsTo(JalanTol::class, 'jalan_tol_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
