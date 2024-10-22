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


    //// LEGER JALAN UTAMA DEPAN
    public function getRambuLaluLintas()
    {
        //RAMBU LALU LINTAS KIRI
        $rambu_lalulintas_kiri_count = DB::table('spatial_rambu_lalulintas_point')
            ->selectRaw('COUNT(spatial_rambu_lalulintas_point.id) as rambu_lalulintas_kiri_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->value('rambu_lalulintas_kiri_count');
        $rambu_lalulintas_kiri_count = json_decode($rambu_lalulintas_kiri_count, true);

        //RAMBU LALU LINTAS KANAN
        $rambu_lalulintas_kanan_count = DB::table('spatial_rambu_lalulintas_point')
            ->selectRaw('COUNT(spatial_rambu_lalulintas_point.id) as rambu_lalulintas_kanan_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->value('rambu_lalulintas_kanan_count');
        $rambu_lalulintas_kanan_count = json_decode($rambu_lalulintas_kanan_count, true);

        //RAMBU LALU LINTAS MEDIAN
        $rambu_lalulintas_median_count = DB::table('spatial_rambu_lalulintas_point')
            ->selectRaw('COUNT(spatial_rambu_lalulintas_point.id) as rambu_lalulintas_median_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->value('rambu_lalulintas_median_count');
        $rambu_lalulintas_median_count = json_decode($rambu_lalulintas_median_count, true);

        return [
            'kiri' => $rambu_lalulintas_kiri_count,
            'kanan' => $rambu_lalulintas_kanan_count,
            'median' => $rambu_lalulintas_median_count
        ];
    }

    public function getGorongGorong()
    {
        $gorong = DB::table('spatial_gorong_gorong_line as gr')
            ->select('gr.*')
            ->join('spatial_segmen_leger_polygon as sl', DB::raw('ST_Contains(sl.geom::geometry, gr.geom::geometry)'), '=', DB::raw('true'))
            ->where('sl.id', 1)
            ->get();
        $gorong = json_decode($gorong, true);

        return $gorong;
    }

    public function getDataGeometrikJalan()
    {
        $data_geometrik_jalan = DB::table('spatial_data_geometrik_jalan_polygon')
            ->select('spatial_data_geometrik_jalan_polygon.*')
            ->where('spatial_data_geometrik_jalan_polygon.id', 1)
            ->get();
        $data_geometrik_jalan = json_decode($data_geometrik_jalan, true);

        return $data_geometrik_jalan;
    }

    public function getPatokKM()
    {
        // PATOK KM KIRI
        $patok_km_kiri_count = DB::table('spatial_patok_km_point')
            ->selectRaw('COUNT(spatial_patok_km_point.id) as patok_km_kiri_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->value('patok_km_kiri_count');
        $patok_km_kiri_count = json_decode($patok_km_kiri_count, true);

        // PATOK KM KANAN
        $patok_km_kanan_count = DB::table('spatial_patok_km_point')
            ->selectRaw('COUNT(spatial_patok_km_point.id) as patok_km_kanan_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->value('patok_km_kanan_count');
        $patok_km_kanan_count = json_decode($patok_km_kanan_count, true);

        // PATOK KM MEDIAN
        $patok_km_median_count = DB::table('spatial_patok_km_point')
            ->selectRaw('COUNT(spatial_patok_km_point.id) as patok_km_median_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->value('patok_km_median_count');
        $patok_km_median_count = json_decode($patok_km_median_count, true);

        return [
            'kiri' => $patok_km_kiri_count,
            'kanan' => $patok_km_kanan_count,
            'median' => $patok_km_median_count
        ];
    }

    public function getPatokHM()
    {
        // PATOK HM KIRI
        $patok_hm_kiri_count = DB::table('spatial_patok_hm_point')
            ->selectRaw('COUNT(spatial_patok_hm_point.id) as patok_hm_kiri_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->value('patok_hm_kiri_count');
        $patok_hm_kiri_count = json_decode($patok_hm_kiri_count, true);

        // PATOK HM KANAN
        $patok_hm_kanan_count = DB::table('spatial_patok_hm_point')
            ->selectRaw('COUNT(spatial_patok_hm_point.id) as patok_hm_kanan_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->value('patok_hm_kanan_count');
        $patok_hm_kanan_count = json_decode($patok_hm_kanan_count, true);

        // PATOK HM MEDIAN
        $patok_hm_median_count = DB::table('spatial_patok_hm_point')
            ->selectRaw('COUNT(spatial_patok_hm_point.id) as patok_hm_median_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->value('patok_hm_median_count');
        $patok_hm_median_count = json_decode($patok_hm_median_count, true);

        return [
            'kiri' => $patok_hm_kiri_count,
            'kanan' => $patok_hm_kanan_count,
            'median' => $patok_hm_median_count
        ];
    }

    public function getPatokLJ()
    {
        // PATOK LJ KIRI
        $patok_lj_kiri_count = DB::table('spatial_patok_lj_point')
            ->selectRaw('COUNT(spatial_patok_lj_point.id) as patok_lj_kiri_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->value('patok_lj_kiri_count');
        $patok_lj_kiri_count = json_decode($patok_lj_kiri_count, true);

        // PATOK LJ KANAN
        $patok_lj_kanan_count = DB::table('spatial_patok_lj_point')
            ->selectRaw('COUNT(spatial_patok_lj_point.id) as patok_lj_kanan_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->value('patok_lj_kanan_count');
        $patok_lj_kanan_count = json_decode($patok_lj_kanan_count, true);

        // PATOK LJ MEDIAN
        $patok_lj_median_count = DB::table('spatial_patok_lj_point')
            ->selectRaw('COUNT(spatial_patok_lj_point.id) as patok_lj_median_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->value('patok_lj_median_count');
        $patok_lj_median_count = json_decode($patok_lj_median_count, true);

        return [
            'kiri' => $patok_lj_kiri_count,
            'kanan' => $patok_lj_kanan_count,
            'median' => $patok_lj_median_count
        ];
    }

    public function getPatokRMJ()
    {
        // PATOK RMJ KIRI
        $patok_rmj_kiri_count = DB::table('spatial_patok_rmj_point')
            ->selectRaw('COUNT(spatial_patok_rmj_point.id) as patok_rmj_kiri_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->value('patok_rmj_kiri_count');
        $patok_rmj_kiri_count = json_decode($patok_rmj_kiri_count, true);

        // PATOK RMJ KANAN
        $patok_rmj_kanan_count = DB::table('spatial_patok_rmj_point')
            ->selectRaw('COUNT(spatial_patok_rmj_point.id) as patok_rmj_kanan_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->value('patok_rmj_kanan_count');
        $patok_rmj_kanan_count = json_decode($patok_rmj_kanan_count, true);

        // PATOK RMJ MEDIAN
        $patok_rmj_median_count = DB::table('spatial_patok_rmj_point')
            ->selectRaw('COUNT(spatial_patok_rmj_point.id) as patok_rmj_median_count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->value('patok_rmj_median_count');
        $patok_rmj_median_count = json_decode($patok_rmj_median_count, true);

        return [
            'kiri' => $patok_rmj_kiri_count,
            'kanan' => $patok_rmj_kanan_count,
            'median' => $patok_rmj_median_count
        ];
    }

    public function getLHR()
    {
        // LHR GOLONGAN I KIRI
        $lhr__kiri_sum_golongan_i = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_i AS NUMERIC)) as lhr__kiri_sum_golongan_i')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kiri_sum_golongan_i');
        $lhr__kiri_sum_golongan_i = json_decode($lhr__kiri_sum_golongan_i, true);

        // LHR GOLONGAN I KANAN
        $lhr__kanan_sum_golongan_i = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_i AS NUMERIC)) as lhr__kanan_sum_golongan_i')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kanan_sum_golongan_i');
        $lhr__kanan_sum_golongan_i = json_decode($lhr__kanan_sum_golongan_i, true);

        // LHR GOLONGAN II KIRI
        $lhr__kiri_sum_golongan_ii = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_ii AS NUMERIC)) as lhr__kiri_sum_golongan_ii')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kiri_sum_golongan_ii');
        $lhr__kiri_sum_golongan_ii = json_decode($lhr__kiri_sum_golongan_ii, true);

        // LHR GOLONGAN II KANAN
        $lhr__kanan_sum_golongan_ii = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_ii AS NUMERIC)) as lhr__kanan_sum_golongan_ii')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kanan_sum_golongan_ii');
        $lhr__kanan_sum_golongan_ii = json_decode($lhr__kanan_sum_golongan_ii, true);

        // LHR GOLONGAN III KIRI
        $lhr__kiri_sum_golongan_iii = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_iii AS NUMERIC)) as lhr__kiri_sum_golongan_iii')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kiri_sum_golongan_iii');
        $lhr__kiri_sum_golongan_iii = json_decode($lhr__kiri_sum_golongan_iii, true);

        // LHR GOLONGAN III KANAN
        $lhr__kanan_sum_golongan_iii = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_iii AS NUMERIC)) as lhr__kanan_sum_golongan_iii')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kanan_sum_golongan_iii');
        $lhr__kanan_sum_golongan_iii = json_decode($lhr__kanan_sum_golongan_iii, true);

        // LHR GOLONGAN IV KIRI
        $lhr__kiri_sum_golongan_iv = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_iv AS NUMERIC)) as lhr__kiri_sum_golongan_iv')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kiri_sum_golongan_iv');
        $lhr__kiri_sum_golongan_iv = json_decode($lhr__kiri_sum_golongan_iv, true);

        // LHR GOLONGAN IV KANAN
        $lhr__kanan_sum_golongan_iv = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_iv AS NUMERIC)) as lhr__kanan_sum_golongan_iv')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kanan_sum_golongan_iv');
        $lhr__kanan_sum_golongan_iv = json_decode($lhr__kanan_sum_golongan_iv, true);

        // LHR GOLONGAN V KIRI
        $lhr__kiri_sum_golongan_v = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_v AS NUMERIC)) as lhr__kiri_sum_golongan_v')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kiri_sum_golongan_v');
        $lhr__kiri_sum_golongan_v = json_decode($lhr__kiri_sum_golongan_v, true);

        // LHR GOLONGAN V KANAN
        $lhr__kanan_sum_golongan_v = DB::table('spatial_lhr_polygon')
            ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_v AS NUMERIC)) as lhr__kanan_sum_golongan_v')
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
            ->value('lhr__kanan_sum_golongan_v');
        $lhr__kanan_sum_golongan_v = json_decode($lhr__kanan_sum_golongan_v, true);

        // // LHR GOLONGAN VI KIRI
        // $lhr__kiri_sum_golongan_vi = DB::table('spatial_lhr_polygon')
        //     ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_vi AS NUMERIC)) as lhr__kiri_sum_golongan_vi')
        //     ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
        //     ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
        //     ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
        //     ->value('lhr__kiri_sum_golongan_vi');
        // $lhr__kiri_sum_golongan_vi = json_decode($lhr__kiri_sum_golongan_vi, true);

        // // LHR GOLONGAN VI KANAN
        // $lhr__kanan_sum_golongan_vi = DB::table('spatial_lhr_polygon')
        //     ->selectRaw('SUM(CAST(spatial_lhr_polygon.gol_vi AS NUMERIC)) as lhr__kanan_sum_golongan_vi')
        //     ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Overlaps(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lhr_polygon.geom::geometry)'), '=', DB::raw('true'))
        //     ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
        //     ->where('spatial_lhr_polygon.segmen_tol', 'MAINROAD')
        //     ->value('lhr__kanan_sum_golongan_vi');
        // $lhr__kanan_sum_golongan_vi = json_decode($lhr__kanan_sum_golongan_vi, true);

        return [
            'golongan_i' => [
                'kiri' => $lhr__kiri_sum_golongan_i,
                'kanan' => $lhr__kanan_sum_golongan_i
            ],
            'golongan_ii' => [
                'kiri' => $lhr__kiri_sum_golongan_ii,
                'kanan' => $lhr__kanan_sum_golongan_ii
            ],
            'golongan_iii' => [
                'kiri' => $lhr__kiri_sum_golongan_iii,
                'kanan' => $lhr__kanan_sum_golongan_iii
            ],
            'golongan_iv' => [
                'kiri' => $lhr__kiri_sum_golongan_iv,
                'kanan' => $lhr__kanan_sum_golongan_iv
            ],
            'golongan_v' => [
                'kiri' => $lhr__kiri_sum_golongan_v,
                'kanan' => $lhr__kanan_sum_golongan_v
            ],
            // 'golongan_vi' => [
            //     'kiri' => $lhr__kiri_sum_golongan_vi,
            //     'kanan' => $lhr__kanan_sum_golongan_vi
            // ]
        ];
    }

    public function getDataJalanUtama()
    {
        //init
        $data = [];
        $data['rambu_lalulintas_count'] = $this->getRambuLaluLintas();
        $data['gorong_gorong'] = $this->getGorongGorong();
        $data['data_geometrik_jalan'] = $this->getDataGeometrikJalan();
        $data['patok_km_count'] = $this->getPatokKM();
        $data['patok_hm_count'] = $this->getPatokHM();
        $data['patok_lj_count'] = $this->getPatokLJ();
        $data['patok_rmj_count'] = $this->getPatokRMJ();
        $data['lhr_sum'] = $this->getLHR();

        return response()->json($data);
    }
}
