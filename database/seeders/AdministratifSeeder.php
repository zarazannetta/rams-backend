<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Teknik\KodeProvinsi;
use App\Models\Teknik\KodeKabkot;
use App\Models\Teknik\KodeKecamatan;
use App\Models\Teknik\KodeDesakel;

class AdministratifSeeder extends Seeder
{
    public function run(): void
    {
        KodeProvinsi::create(
            [
                'kode' => '17',
                'nama' => 'LAMPUNG',
            ]
        );

        KodeKabkot::create(
            [
                'kode' => '02',
                'nama' => 'LAMPUNG TENGAH',
            ]
        );

        KodeKabkot::create(
            [
                'kode' => '09',
                'nama' => 'PESAWARAN',
            ]
        );

        KodeKecamatan::create(
            [
                'kode' => '03',
                'nama' => 'TEGINENENG',
            ]
        );

        KodeKecamatan::create(
            [
                'kode' => '04',
                'nama' => 'GUNUNG SUGIH',
            ]
        );

        KodeKecamatan::create(
            [
                'kode' => '07',
                'nama' => 'TERBANGGI BESAR',
            ]
        );

        KodeKecamatan::create(
            [
                'kode' => '14',
                'nama' => 'BUMI RATU NUBAN',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '01',
                'nama' => 'SIDOWARAS',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '01',
                'nama' => 'SUKA JAWA',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '02',
                'nama' => 'SEPUTIH JAYA',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '02',
                'nama' => 'SIDOKERTO',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '03',
                'nama' => 'SUKA JADI',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '04',
                'nama' => 'NEGARA WATU WATES',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '04',
                'nama' => 'YAKUM JAYA',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '05',
                'nama' => 'GUNUNG SUGIH BARU',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '05',
                'nama' => 'GUNUNG SARI',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '05',
                'nama' => 'BANDAR JAYA TIMUR',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '05',
                'nama' => 'TERBANGGI BESAR',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '06',
                'nama' => 'INDRA PUTRA SUBING',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '06',
                'nama' => 'WATES',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '08',
                'nama' => 'GUNUNG SUGIH',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '09',
                'nama' => 'KARANG ENDAH',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '09',
                'nama' => 'TULUNG KAKAN',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '10',
                'nama' => 'BUMI RATU',
            ]
        );

        KodeDesakel::create(
            [
                'kode' => '14',
                'nama' => 'TERBANGGI SUBING',
            ]
        );
    }
}
