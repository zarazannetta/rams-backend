<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\JalanTol;
use App\Models\Teknik\LegerDetailData;
use App\Models\Teknik\LegerJalan;
use App\Models\Teknik\LegerRamp;
use App\Models\Teknik\LegerAkses;
use App\Models\Teknik\KodeProvinsi;
use App\Models\Teknik\KodeKabkot;
use App\Models\Teknik\KodeKecamatan;
use App\Models\Teknik\KodeDesakel;
use App\Models\Teknik\JenisBangunan;
use App\Models\Teknik\JenisKonstruksi;
use App\Models\Teknik\JenisLapis;
use App\Models\Teknik\JenisLingkungan;
use App\Models\Teknik\JenisSaluran;
use App\Models\Teknik\JenisSarana;

class LegerController extends Controller
{
    public function generate(Request $request)
    {
        $data = $request->all();
        return response()->json($data);
    }
}
