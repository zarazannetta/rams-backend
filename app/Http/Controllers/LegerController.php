<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\JalanTol;
use App\Models\Leger;
use App\Models\Teknik\LegerJalan;

class LegerController extends Controller
{
    public function generate(Request $request)
    {
        $user = Auth::user()->id;
        $leger_identifikasi = $request->leger_identifikasi_id;
        $jenis_leger = $request->jenis_leger;
        $detail_data = $request->detail_data;

        // return response()->json($data);
    }
}
