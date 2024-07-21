<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Teknik\Jalan\LegerJalan;

class LegerController extends Controller
{
    public function getData($kode_leger)
    {
        $leger_jalan = LegerJalan::with(
                                'dataJalanIdentifikasi', 
                                'dataJalanTeknik1',
                                'dataJalanTeknik2Lapis',
                                'dataJalanTeknik2Median',
                                'dataJalanTeknik2BahuJalan',
                                'dataJalanTeknik3Goronggorong',
                                'dataJalanTeknik3Saluran',
                                'dataJalanTeknik3Bangunan',
                                'dataJalanTeknik4',
                                'dataJalanTeknik5Utilitas',
                                'dataJalanTeknik5Bangunan',
                                'dataJalanLHR',
                                'dataJalanGeometrik',
                                'dataJalanLingkungan',
                                'dataJalanLainnya',
                                'dataJalanIdentifikasi.kodeProvinsi', 
                                'dataJalanIdentifikasi.kodeKabkot', 
                                'dataJalanIdentifikasi.kodeKecamatan', 
                                'dataJalanIdentifikasi.kodeDesakel'
                            )
                            ->where('kode_leger', $kode_leger)->first();
        return response()->json($leger_jalan);
    }

    public function generate(Request $request)
    {
        $data = null;
        return response()->json($data);
    }
}
