<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create(
            [
                'email' => 'wawansetyadi33@gmail.com',
                'username' => 'OneStyd',
                'password' => Hash::make('123456'),
                'fullname' => 'Wawan Setyadi',
                'role_id' => 1,
            ]
        );
        User::create(
            [
                'email' => 'rams_admin@arung.com',
                'username' => 'Arung_Rams01',
                'password' => Hash::make('Adminr4m5'),
                'fullname' => 'Admin Arung',
                'role_id' => 1,
            ]
        );
    }
}
