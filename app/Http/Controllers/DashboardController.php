<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JalanTol;
use App\Models\Leger;
use App\Models\Spatial\LHRPolygon;
use App\Models\Spatial\IRIPolygon;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $total_ruas = JalanTol::count();
        $jumlah_user = User::count();
        if (Auth::user()->id == 1) {
            $jumlah_ruas_user = JalanTol::count();
            $jumlah_leger_user = Leger::count();
            $lhr = LHRPolygon::get();
            $iri = IRIPolygon::get();
        } else {
            $jumlah_ruas_user = JalanTol::where('user_id', Auth::user()->id)->count();
            $jumlah_leger_user = Leger::where('user_id', Auth::user()->id)->count();
            $lhr = LHRPolygon::whereRelation('jalanTol', 'user_id', Auth::user()->id)->get();
            $iri = IRIPolygon::whereRelation('jalanTol', 'user_id', Auth::user()->id)->get();
        }

        // LHR
        $lhr_gol_i = $lhr->sum('gol_i');
        $lhr_gol_ii = $lhr->sum('gol_ii');
        $lhr_gol_iii = $lhr->sum('gol_iii');
        $lhr_gol_iv = $lhr->sum('gol_iv');
        $lhr_gol_v = $lhr->sum('gol_v');

        // IRI
        $iri_baik = $iri->where('nilai_iri', '<=', 4)->count();
        $iri_sedang = $iri->where('nilai_iri', '>', 4)->where('nilai_iri', '<=', 8)->count();
        $iri_rusak_ringan = $iri->where('nilai_iri', '>', 8)->where('nilai_iri', '<=', 12)->count();
        $iri_rusak_berat = $iri->where('nilai_iri', '>', 12)->count();

        return response()->json([
            'total_ruas' => $total_ruas,
            'jumlah_user' => $jumlah_user,
            'jumlah_ruas_user' => $jumlah_ruas_user,
            'jumlah_leger_user' => $jumlah_leger_user,
            'lhr_gol_i' => $lhr_gol_i,
            'lhr_gol_ii' => $lhr_gol_ii,
            'lhr_gol_iii' => $lhr_gol_iii,
            'lhr_gol_iv' => $lhr_gol_iv,
            'lhr_gol_v' => $lhr_gol_v,
            'iri_baik' => $iri_baik,
            'iri_sedang' => $iri_sedang,
            'iri_rusak_ringan' => $iri_rusak_ringan,
            'iri_rusak_berat' => $iri_rusak_berat,
        ]);
    }
}
