<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
     public function getKMOptions() 
    {
        $kmOptions = DB::table('spatial_patok_km_point')
            ->select('km')
            ->distinct()
            ->orderBy('km')
            ->get();

        return response()->json(['data' => $kmOptions]);
    }
}