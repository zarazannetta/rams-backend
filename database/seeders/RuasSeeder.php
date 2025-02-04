<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\JalanTol;

class RuasSeeder extends Seeder
{
    public function run(): void
    {
        JalanTol::create(
            [
                'user_id' => 2,
                'nama' => 'Bakauheni - Terbanggi Besar',
                'kode' => 'BHTB01',
                'tahun' => '2019',
            ]
        );
    }
}
