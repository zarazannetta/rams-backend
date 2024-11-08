<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Teknik\Jalan\LegerJalan;

use App\Models\JalanTol;
use App\Models\Spatial\AdministratifPolygon;
use App\Models\Spatial\BatasDesaLine;
use App\Models\Spatial\BoxCulvertLine;
use App\Models\Spatial\BPTLine;
use App\Models\Spatial\BronjongLine;
use App\Models\Spatial\ConcreteBarrierLine;
use App\Models\Spatial\DataGeometrikJalanPolygon;
use App\Models\Spatial\GerbangLine;
use App\Models\Spatial\GerbangPoint;
use App\Models\Spatial\GorongGorongLine;
use App\Models\Spatial\GuardrailLine;
use App\Models\Spatial\IRIPolygon;
use App\Models\Spatial\JalanLine;
use App\Models\Spatial\JembatanPoint;
use App\Models\Spatial\JembatanPolygon;
use App\Models\Spatial\LampuLalulintasPoint;
use App\Models\Spatial\LapisPermukaanPolygon;
use App\Models\Spatial\LapisPondasiAtas1Polygon;
use App\Models\Spatial\LapisPondasiAtas2Polygon;
use App\Models\Spatial\LapisPondasiBawahPolygon;
use App\Models\Spatial\LHRPolygon;
use App\Models\Spatial\ListrikBawahtanahLine;
use App\Models\Spatial\ManholePoint;
use App\Models\Spatial\MarkaLine;
use App\Models\Spatial\PagarOperasionalLine;
use App\Models\Spatial\PatokHMPoint;
use App\Models\Spatial\PatokKMPoint;
use App\Models\Spatial\PatokLJPoint;
use App\Models\Spatial\PatokPemanduPoint;
use App\Models\Spatial\PatokRMJPoint;
use App\Models\Spatial\PatokROWPoint;
use App\Models\Spatial\PitaKejutLine;
use App\Models\Spatial\RambuLalulintasPoint;
use App\Models\Spatial\RambuPenunjukarahPoint;
use App\Models\Spatial\ReflektorPoint;
use App\Models\Spatial\RiolLine;
use App\Models\Spatial\RumahKabelPoint;
use App\Models\Spatial\RuwasjaPolygon;
use App\Models\Spatial\SaluranLine;
use App\Models\Spatial\SegmenKonstruksiPolygon;
use App\Models\Spatial\SegmenLegerPolygon;
use App\Models\Spatial\SegmenPerlengkapanPolygon;
use App\Models\Spatial\SegmenSeksiPolygon;
use App\Models\Spatial\SegmenTolPolygon;
use App\Models\Spatial\StaTextPoint;
use App\Models\Spatial\SungaiLine;
use App\Models\Spatial\TeleponBawahtanahLine;
use App\Models\Spatial\TiangListrikPoint;
use App\Models\Spatial\TiangTeleponPoint;
use App\Models\Spatial\VMSPoint;

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


    public function getRuasSegmen(Request $request)
    {
        // return [
        //     "hello" => "world"
        // ];

        //RUAS
        $ruas = DB::table('jalan_tol')
        ->selectRaw('jalan_tol.id, jalan_tol.nama, jalan_tol.tahun')
        ->get();


        foreach($ruas as $r)
        {
            $r->segmen = DB::table('spatial_segmen_leger_polygon')
            ->where('spatial_segmen_leger_polygon.jalan_tol_id', $r->id)
            ->where('spatial_segmen_leger_polygon.id_leger', 'like', 'M%') // Only select id_leger that starts with 'M.'
            ->selectRaw('spatial_segmen_leger_polygon.id_leger')
            ->orderBy('spatial_segmen_leger_polygon.id_leger', 'asc') // Sort in ascending order
            ->get();
        }
        $ruas = json_decode($ruas, true);

        return $ruas;
    }

    public function getSegmen(Request $request)
    {
        $segmen = DB::table('spatial_segmen_leger_polygon')
        ->where('spatial_segmen_leger_polygon.jalan_tol_id', $request->jalan_tol_id)
        ->where('spatial_segmen_leger_polygon.id_leger', 'like', 'M%') // Only select id_leger that starts with 'M.'
        ->selectRaw('spatial_segmen_leger_polygon.id_leger')
        ->orderBy('spatial_segmen_leger_polygon.id_leger', 'asc') // Sort in ascending order
        ->get();

        $segmen = json_decode($segmen, true);

        return $segmen;
    }

    //// LEGER JALAN UTAMA DEPAN
    public function getRambuLaluLintas(Request $request)
    {

        //RAMBU LALU LINTAS KIRI
        $rambu_lalulintas_kiri_count = DB::table('spatial_rambu_lalulintas_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->selectRaw('COUNT(spatial_rambu_lalulintas_point.id) as rambu_lalulintas_kiri_count')
            ->value('rambu_lalulintas_kiri_count');
        $rambu_lalulintas_kiri_count = json_decode($rambu_lalulintas_kiri_count, true);

        //RAMBU LALU LINTAS KANAN
        $rambu_lalulintas_kanan_count = DB::table('spatial_rambu_lalulintas_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->selectRaw('COUNT(spatial_rambu_lalulintas_point.id) as rambu_lalulintas_kanan_count')
            ->value('rambu_lalulintas_kanan_count');
        $rambu_lalulintas_kanan_count = json_decode($rambu_lalulintas_kanan_count, true);

        //RAMBU LALU LINTAS MEDIAN
        $rambu_lalulintas_median_count = DB::table('spatial_rambu_lalulintas_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_rambu_lalulintas_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->selectRaw('COUNT(spatial_rambu_lalulintas_point.id) as rambu_lalulintas_median_count')
            ->value('rambu_lalulintas_median_count');
        $rambu_lalulintas_median_count = json_decode($rambu_lalulintas_median_count, true);

        return [
            'kiri' => $rambu_lalulintas_kiri_count,
            'kanan' => $rambu_lalulintas_kanan_count,
            'median' => $rambu_lalulintas_median_count
        ];
    }

    public function getGorongGorong(Request $request)
    {
        $gorong = DB::table('spatial_gorong_gorong_line as gr')
            ->select('gr.*')
            ->join('spatial_segmen_leger_polygon as sl', DB::raw('ST_Contains(sl.geom::geometry, gr.geom::geometry)'), '=', DB::raw('true'))
            ->where('sl.id_leger', $request->leger_id)
            ->get();
        $gorong = json_decode($gorong, true);

        return $gorong;
    }

    public function getDataGeometrikJalan(Request $request)
    {
        $data_geometrik_jalan = DB::table('spatial_data_geometrik_jalan_polygon')
            ->select('spatial_data_geometrik_jalan_polygon.*')
            ->where('spatial_data_geometrik_jalan_polygon.id_leger', $request->leger_id)
            ->get();
        $data_geometrik_jalan = json_decode($data_geometrik_jalan, true);

        return $data_geometrik_jalan;
    }

    public function getPatokKM(Request $request)
    {
        // PATOK KM KIRI
        $patok_km_kiri_count = DB::table('spatial_patok_km_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->selectRaw('COUNT(spatial_patok_km_point.id) as patok_km_kiri_count')
            ->value('patok_km_kiri_count');
        $patok_km_kiri_count = json_decode($patok_km_kiri_count, true);

        // PATOK KM KANAN
        $patok_km_kanan_count = DB::table('spatial_patok_km_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->selectRaw('COUNT(spatial_patok_km_point.id) as patok_km_kanan_count')
            ->value('patok_km_kanan_count');
        $patok_km_kanan_count = json_decode($patok_km_kanan_count, true);

        // PATOK KM MEDIAN
        $patok_km_median_count = DB::table('spatial_patok_km_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_km_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->selectRaw('COUNT(spatial_patok_km_point.id) as patok_km_median_count')
            ->value('patok_km_median_count');
        $patok_km_median_count = json_decode($patok_km_median_count, true);

        return [
            'kiri' => $patok_km_kiri_count,
            'kanan' => $patok_km_kanan_count,
            'median' => $patok_km_median_count
        ];
    }

    public function getPatokHM(Request $request)
    {
        // PATOK HM KIRI
        $patok_hm_kiri_count = DB::table('spatial_patok_hm_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->selectRaw('COUNT(spatial_patok_hm_point.id) as patok_hm_kiri_count')
            ->value('patok_hm_kiri_count');
        $patok_hm_kiri_count = json_decode($patok_hm_kiri_count, true);

        // PATOK HM KANAN
        $patok_hm_kanan_count = DB::table('spatial_patok_hm_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->selectRaw('COUNT(spatial_patok_hm_point.id) as patok_hm_kanan_count')
            ->value('patok_hm_kanan_count');
        $patok_hm_kanan_count = json_decode($patok_hm_kanan_count, true);

        // PATOK HM MEDIAN
        $patok_hm_median_count = DB::table('spatial_patok_hm_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_hm_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->selectRaw('COUNT(spatial_patok_hm_point.id) as patok_hm_median_count')
            ->value('patok_hm_median_count');
        $patok_hm_median_count = json_decode($patok_hm_median_count, true);

        return [
            'kiri' => $patok_hm_kiri_count,
            'kanan' => $patok_hm_kanan_count,
            'median' => $patok_hm_median_count
        ];
    }

    public function getPatokLJ(Request $request)
    {
        // PATOK LJ KIRI
        $patok_lj_kiri_count = DB::table('spatial_patok_lj_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->selectRaw('COUNT(spatial_patok_lj_point.id) as patok_lj_kiri_count')
            ->value('patok_lj_kiri_count');
        $patok_lj_kiri_count = json_decode($patok_lj_kiri_count, true);

        // PATOK LJ KANAN
        $patok_lj_kanan_count = DB::table('spatial_patok_lj_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->selectRaw('COUNT(spatial_patok_lj_point.id) as patok_lj_kanan_count')
            ->value('patok_lj_kanan_count');
        $patok_lj_kanan_count = json_decode($patok_lj_kanan_count, true);

        // PATOK LJ MEDIAN
        $patok_lj_median_count = DB::table('spatial_patok_lj_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_lj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->selectRaw('COUNT(spatial_patok_lj_point.id) as patok_lj_median_count')
            ->value('patok_lj_median_count');
        $patok_lj_median_count = json_decode($patok_lj_median_count, true);

        return [
            'kiri' => $patok_lj_kiri_count,
            'kanan' => $patok_lj_kanan_count,
            'median' => $patok_lj_median_count
        ];
    }

    public function getPatokRMJ(Request $request)
    {
        // PATOK RMJ KIRI
        $patok_rmj_kiri_count = DB::table('spatial_patok_rmj_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->selectRaw('COUNT(spatial_patok_rmj_point.id) as patok_rmj_kiri_count')
            ->value('patok_rmj_kiri_count');
        $patok_rmj_kiri_count = json_decode($patok_rmj_kiri_count, true);

        // PATOK RMJ KANAN
        $patok_rmj_kanan_count = DB::table('spatial_patok_rmj_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->selectRaw('COUNT(spatial_patok_rmj_point.id) as patok_rmj_kanan_count')
            ->value('patok_rmj_kanan_count');
        $patok_rmj_kanan_count = json_decode($patok_rmj_kanan_count, true);

        // PATOK RMJ MEDIAN
        $patok_rmj_median_count = DB::table('spatial_patok_rmj_point')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_patok_rmj_point.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->selectRaw('COUNT(spatial_patok_rmj_point.id) as patok_rmj_median_count')
            ->value('patok_rmj_median_count');
        $patok_rmj_median_count = json_decode($patok_rmj_median_count, true);

        return [
            'kiri' => $patok_rmj_kiri_count,
            'kanan' => $patok_rmj_kanan_count,
            'median' => $patok_rmj_median_count
        ];
    }

    public function getLHR(Request $request)
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

    public function getPagarOperasional(Request $request)
    {
        // PAGAR OPERASIONAL KIRI
        $pagar_operasional_kiri = DB::table('spatial_pagar_operasional_line')
            ->selectRaw('COUNT(spatial_pagar_operasional_line.id) as count, SUM(ST_Length(spatial_pagar_operasional_line.geom::geography)) as length')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_operasional_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_pagar_operasional_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // PAGAR OPERASIONAL KANAN
        $pagar_operasional_kanan = DB::table('spatial_pagar_operasional_line')
            ->selectRaw('COUNT(spatial_pagar_operasional_line.id) as count, SUM(ST_Length(spatial_pagar_operasional_line.geom::geography)) as length')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_operasional_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_pagar_operasional_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // PAGAR OPERASIONAL MEDIAN
        $pagar_operasional_median = DB::table('spatial_pagar_operasional_line')
            ->selectRaw('COUNT(spatial_pagar_operasional_line.id) as count, SUM(ST_Length(spatial_pagar_operasional_line.geom::geography)) as length')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_operasional_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_pagar_operasional_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->get();

        return [
            'kiri' => $pagar_operasional_kiri,
            'kanan' => $pagar_operasional_kanan,
            'median' => $pagar_operasional_median
        ];
    }

    public function getMarkaJalan(Request $request)
    {
        // MARKA JALAN KIRI
        $marka_jalan_kiri = DB::table('spatial_marka_line')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_marka_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->selectRaw('COUNT(spatial_marka_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_marka_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->get();

        // MARKA JALAN KANAN
        $marka_jalan_kanan = DB::table('spatial_marka_line')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_marka_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->selectRaw('COUNT(spatial_marka_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_marka_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->get();

        // MARKA JALAN MEDIAN
        $marka_jalan_median = DB::table('spatial_marka_line')
            ->selectRaw('COUNT(spatial_marka_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_marka_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_marka_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->selectRaw('COUNT(spatial_marka_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_marka_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->get();

        return [
            'kiri' => $marka_jalan_kiri,
            'kanan' => $marka_jalan_kanan,
            'median' => $marka_jalan_median
        ];
    }

    public function getTeleponAtasTanah(Request $request)
    {
        // TELEPON ATAS TANAH KIRI
        $telepon_atas_tanah_kiri = DB::table('spatial_telepon_atastanah_line')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_telepon_atastanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_telepon_atastanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->selectRaw('COUNT(spatial_telepon_atastanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_telepon_atastanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->get();

        // TELEPON ATAS TANAH KANAN
        $telepon_atas_tanah_kanan = DB::table('spatial_telepon_atastanah_line')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_telepon_atastanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_telepon_atastanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->selectRaw('COUNT(spatial_telepon_atastanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_telepon_atastanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->get();

        // TELEPON ATAS TANAH MEDIAN
        $telepon_atas_tanah_median = DB::table('spatial_telepon_atastanah_line')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_telepon_atastanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_telepon_atastanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->selectRaw('COUNT(spatial_telepon_atastanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_telepon_atastanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->get();

        return [
            'kiri' => $telepon_atas_tanah_kiri,
            'kanan' => $telepon_atas_tanah_kanan,
            'median' => $telepon_atas_tanah_median
        ];
    }

    public function getTeleponBawahTanah(Request $request)
    {
        // TELEPON BAWAH TANAH KIRI
        $telepon_bawah_tanah_kiri = DB::table('spatial_telepon_bawahtanah_line')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_telepon_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_telepon_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->selectRaw('COUNT(spatial_telepon_bawahtanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_telepon_bawahtanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->get();

        // TELEPON BAWAH TANAH KANAN
        $telepon_bawah_tanah_kanan = DB::table('spatial_telepon_bawahtanah_line')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_telepon_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_telepon_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->selectRaw('COUNT(spatial_telepon_bawahtanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_telepon_bawahtanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->get();

        // TELEPON BAWAH TANAH MEDIAN
        $telepon_bawah_tanah_median = DB::table('spatial_telepon_bawahtanah_line')
            ->join('spatial_segmen_leger_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_telepon_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->join('spatial_segmen_perlengkapan_polygon', function ($join) {
                $join->on(DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_telepon_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'));
            })
            ->where('spatial_segmen_leger_polygon.id', 1)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->selectRaw('COUNT(spatial_telepon_bawahtanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_telepon_bawahtanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->get();

        return [
            'kiri' => $telepon_bawah_tanah_kiri,
            'kanan' => $telepon_bawah_tanah_kanan,
            'median' => $telepon_bawah_tanah_median
        ];
    }

    public function getListrikAtasTanah(Request $request)
    {
        // LISTRIK ATAS KIRI
        $listrik_atas_kiri = DB::table('spatial_listrik_atastanah_line')
            ->selectRaw('COUNT(spatial_listrik_atastanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_listrik_atastanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_listrik_atastanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_listrik_atastanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // LISTRIK ATAS KANAN
        $listrik_atas_kanan = DB::table('spatial_listrik_atastanah_line')
            ->selectRaw('COUNT(spatial_listrik_atastanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_listrik_atastanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_listrik_atastanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_listrik_atastanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // LISTRIK ATAS MEDIAN
        $listrik_atas_median = DB::table('spatial_listrik_atastanah_line')
            ->selectRaw('COUNT(spatial_listrik_atastanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_listrik_atastanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_listrik_atastanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_listrik_atastanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->get();

        return [
            'kiri' => $listrik_atas_kiri,
            'kanan' => $listrik_atas_kanan,
            'median' => $listrik_atas_median
        ];
    }

    public function getListrikBawahTanah(Request $request)
    {
        // LISTRIK BAWAH TANAH KIRI
        $listrik_bawah_tanah_kiri = DB::table('spatial_listrik_bawahtanah_line')
            ->selectRaw('COUNT(spatial_listrik_bawahtanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_listrik_bawahtanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_listrik_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_listrik_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // LISTRIK BAWAH TANAH KANAN
        $listrik_bawah_tanah_kanan = DB::table('spatial_listrik_bawahtanah_line')
            ->selectRaw('COUNT(spatial_listrik_bawahtanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_listrik_bawahtanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_listrik_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_listrik_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // LISTRIK BAWAH TANAH MEDIAN
        $listrik_bawah_tanah_median = DB::table('spatial_listrik_bawahtanah_line')
            ->selectRaw('COUNT(spatial_listrik_bawahtanah_line.id) as count, SUM(ST_Length(ST_Intersection(spatial_listrik_bawahtanah_line.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as length')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_listrik_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_listrik_bawahtanah_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->get();

        return [
            'kiri' => $listrik_bawah_tanah_kiri,
            'kanan' => $listrik_bawah_tanah_kanan,
            'median' => $listrik_bawah_tanah_median
        ];
    }

    public function getManhole(Request $request)
    {
        // MANHOLE KIRI
        $manhole_kiri = DB::table('spatial_manhole_point')
            ->selectRaw('*')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_manhole_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_manhole_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // MANHOLE KANAN
        $manhole_kanan = DB::table('spatial_manhole_point')
            ->selectRaw('*')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_manhole_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_manhole_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // MANHOLE MEDIAN
        $manhole_median = DB::table('spatial_manhole_point')
            ->selectRaw('*')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_manhole_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_manhole_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->get();

        return [
            'kiri' => $manhole_kiri,
            'kanan' => $manhole_kanan,
            'median' => $manhole_median
        ];
    }

    public function getSaluran(Request $request)
    {
        // SALURAN KIRI
        $saluran_kiri = DB::table('spatial_saluran_line')
            ->selectRaw('spatial_saluran_line.jenis_material, spatial_saluran_line.panjang, spatial_saluran_line.tinggi, spatial_saluran_line.kondisi')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_saluran_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_saluran_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // SALURAN KANAN
        $saluran_kanan = DB::table('spatial_saluran_line')
            ->selectRaw('spatial_saluran_line.jenis_material, spatial_saluran_line.panjang, spatial_saluran_line.tinggi, spatial_saluran_line.kondisi')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_saluran_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_saluran_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // SALURAN MEDIAN
        $saluran_median = DB::table('spatial_saluran_line')
            ->selectRaw('spatial_saluran_line.jenis_material, spatial_saluran_line.panjang, spatial_saluran_line.tinggi, spatial_saluran_line.kondisi')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_saluran_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_saluran_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'MEDIAN')
            ->get();

        return [
            'kiri' => $saluran_kiri,
            'kanan' => $saluran_kanan,
            'median' => $saluran_median
        ];
    }

    public function getBadanJalan(Request $request)
    {
        //// BADAN JALAN LAPIS PERMUKAAN KIRI
        // BADAN JALAN LAPIS PERMUKAAN LAJUR 1 KIRI
        $badan_jalan_lapis_permukaan_lajur_1_kiri = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 1')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BADAN JALAN LAPIS PERMUKAAN LAJUR 2 KIRI
        $badan_jalan_lapis_permukaan_lajur_2_kiri = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 2')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BADAN JALAN LAPIS PERMUKAAN LAJUR 3 KIRI
        $badan_jalan_lapis_permukaan_lajur_3_kiri = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 3')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BADAN JALAN LAPIS PERMUKAAN LAJUR 4 KIRI
        $badan_jalan_lapis_permukaan_lajur_4_kiri = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 4')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //// BADAN JALAN LAPIS PERMUKAAN KANAN
        // BADAN JALAN LAPIS PERMUKAAN LAJUR 1 KANAN
        $badan_jalan_lapis_permukaan_lajur_1_kanan = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 1')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BADAN JALAN LAPIS PERMUKAAN LAJUR 2 KANAN
        $badan_jalan_lapis_permukaan_lajur_2_kanan = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 2')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BADAN JALAN LAPIS PERMUKAAN LAJUR 3 KANAN
        $badan_jalan_lapis_permukaan_lajur_3_kanan = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 3')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BADAN JALAN LAPIS PERMUKAAN LAJUR 4 KANAN
        $badan_jalan_lapis_permukaan_lajur_4_kanan = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 4')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        //// BADAN JALAN LAPIS PONDASI ATAS KIRI
        // BADAN JALAN LAPIS PONDASI ATAS LAJUR 1 KIRI
        $badan_jalan_lapis_pondasi_atas_lajur_1_kiri = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 1')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BADAN JALAN LAPIS PONDASI ATAS LAJUR 2 KIRI
        $badan_jalan_lapis_pondasi_atas_lajur_2_kiri = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 2')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BADAN JALAN LAPIS PONDASI ATAS LAJUR 3 KIRI
        $badan_jalan_lapis_pondasi_atas_lajur_3_kiri = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 3')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BADAN JALAN LAPIS PONDASI ATAS LAJUR 4 KIRI
        $badan_jalan_lapis_pondasi_atas_lajur_4_kiri = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 4')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //// BADAN JALAN LAPIS PONDASI ATAS KANAN
        // BADAN JALAN LAPIS PONDASI ATAS LAJUR 1 KANAN
        $badan_jalan_lapis_pondasi_atas_lajur_1_kanan = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 1')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BADAN JALAN LAPIS PONDASI ATAS LAJUR 2 KANAN
        $badan_jalan_lapis_pondasi_atas_lajur_2_kanan = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 2')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BADAN JALAN LAPIS PONDASI ATAS LAJUR 3 KANAN
        $badan_jalan_lapis_pondasi_atas_lajur_3_kanan = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 3')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BADAN JALAN LAPIS PONDASI ATAS LAJUR 4 KANAN
        $badan_jalan_lapis_pondasi_atas_lajur_4_kanan = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 4')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        //// BADAN JALAN LAPIS PONDASI BAWAH KIRI
        // BADAN JALAN LAPIS PONDASI BAWAH LAJUR 1 KIRI
        $badan_jalan_lapis_pondasi_bawah_lajur_1_kiri = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 1')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BADAN JALAN LAPIS PONDASI BAWAH LAJUR 2 KIRI
        $badan_jalan_lapis_pondasi_bawah_lajur_2_kiri = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 2')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BADAN JALAN LAPIS PONDASI BAWAH LAJUR 3 KIRI
        $badan_jalan_lapis_pondasi_bawah_lajur_3_kiri = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 3')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BADAN JALAN LAPIS PONDASI BAWAH LAJUR 4 KIRI
        $badan_jalan_lapis_pondasi_bawah_lajur_4_kiri = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 4')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //// BADAN JALAN LAPIS PONDASI BAWAH KANAN
        // BADAN JALAN LAPIS PONDASI BAWAH LAJUR 1 KANAN
        $badan_jalan_lapis_pondasi_bawah_lajur_1_kanan = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 1')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BADAN JALAN LAPIS PONDASI BAWAH LAJUR 2 KANAN
        $badan_jalan_lapis_pondasi_bawah_lajur_2_kanan = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 2')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BADAN JALAN LAPIS PONDASI BAWAH LAJUR 3 KANAN
        $badan_jalan_lapis_pondasi_bawah_lajur_3_kanan = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 3')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BADAN JALAN LAPIS PONDASI BAWAH LAJUR 4 KANAN
        $badan_jalan_lapis_pondasi_bawah_lajur_4_kanan = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 4')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'lapis_permukaan' => [
                'kiri' => [
                    'lajur_1' => $badan_jalan_lapis_permukaan_lajur_1_kiri,
                    'lajur_2' => $badan_jalan_lapis_permukaan_lajur_2_kiri,
                    'lajur_3' => $badan_jalan_lapis_permukaan_lajur_3_kiri,
                    'lajur_4' => $badan_jalan_lapis_permukaan_lajur_4_kiri,
                ],
                'kanan' => [
                    'lajur_1' => $badan_jalan_lapis_permukaan_lajur_1_kanan,
                    'lajur_2' => $badan_jalan_lapis_permukaan_lajur_2_kanan,
                    'lajur_3' => $badan_jalan_lapis_permukaan_lajur_3_kanan,
                    'lajur_4' => $badan_jalan_lapis_permukaan_lajur_4_kanan,
                ],
            ],
            'lapis_pondasi_atas' => [
                'kiri' => [
                    'lajur_1' => $badan_jalan_lapis_pondasi_atas_lajur_1_kiri,
                    'lajur_2' => $badan_jalan_lapis_pondasi_atas_lajur_2_kiri,
                    'lajur_3' => $badan_jalan_lapis_pondasi_atas_lajur_3_kiri,
                    'lajur_4' => $badan_jalan_lapis_pondasi_atas_lajur_4_kiri,
                ],
                'kanan' => [
                    'lajur_1' => $badan_jalan_lapis_pondasi_atas_lajur_1_kanan,
                    'lajur_2' => $badan_jalan_lapis_pondasi_atas_lajur_2_kanan,
                    'lajur_3' => $badan_jalan_lapis_pondasi_atas_lajur_3_kanan,
                    'lajur_4' => $badan_jalan_lapis_pondasi_atas_lajur_4_kanan,
                ],
            ],
            'lapis_pondasi_bawah' => [
                'kiri' => [
                    'lajur_1' => $badan_jalan_lapis_pondasi_bawah_lajur_1_kiri,
                    'lajur_2' => $badan_jalan_lapis_pondasi_bawah_lajur_2_kiri,
                    'lajur_3' => $badan_jalan_lapis_pondasi_bawah_lajur_3_kiri,
                    'lajur_4' => $badan_jalan_lapis_pondasi_bawah_lajur_4_kiri,
                ],
                'kanan' => [
                    'lajur_1' => $badan_jalan_lapis_pondasi_bawah_lajur_1_kanan,
                    'lajur_2' => $badan_jalan_lapis_pondasi_bawah_lajur_2_kanan,
                    'lajur_3' => $badan_jalan_lapis_pondasi_bawah_lajur_3_kanan,
                    'lajur_4' => $badan_jalan_lapis_pondasi_bawah_lajur_4_kanan,
                ],
            ],
        ];
    }

    public function getMedian(Request $request)
    {
        // MEDIAN
        $median = DB::table('spatial_segmen_konstruksi_polygon')
            ->selectRaw('spatial_segmen_konstruksi_polygon.lebar')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_segmen_konstruksi_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'MEDIAN')
            ->get()->first();

        return $median;
    }

    public function getBahuJalan(Request $request)
    {
        // BAHU JALAN LAPIS PERMUKAAN KIRI LUAR
        $bahu_jalan_lapis_permukaan_kiri_luar = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU LUAR')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BAHU JALAN LAPIS PERMUKAAN KANAN LUAR
        $bahu_jalan_lapis_permukaan_kanan_luar = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU LUAR')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        // BAHU KANAN LAPIS PERMUKAAN KIRI DALAM
        $bahu_jalan_lapis_permukaan_kiri_dalam = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU DALAM')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        // BAHU JALAN LAPIS PERMUKAAN KANAN DALAM
        $bahu_jalan_lapis_permukaan_kanan_dalam = DB::table('spatial_lapis_permukaan_polygon')
            ->selectRaw('spatial_lapis_permukaan_polygon.tebal, spatial_lapis_permukaan_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_permukaan_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU DALAM')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        //BAHU JALAN LAPIS PONDASI ATAS KIRI LUAR
        $bahu_jalan_lapis_pondasi_atas_kiri_luar = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU LUAR')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //BAHU JALAN LAPIS PONDASI ATAS KANAN LUAR
        $bahu_jalan_lapis_pondasi_atas_kanan_luar = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU LUAR')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        //BAHU JALAN LAPIS PONDASI ATAS KIRI DALAM
        $bahu_jalan_lapis_pondasi_atas_kiri_dalam = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU DALAM')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //BAHU JALAN LAPIS PONDASI ATAS KANAN DALAM
        $bahu_jalan_lapis_pondasi_atas_kanan_dalam = DB::table('spatial_lapis_pondasi_atas1_polygon')
            ->selectRaw('spatial_lapis_pondasi_atas1_polygon.tebal, spatial_lapis_pondasi_atas1_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_atas1_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU DALAM')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        //BAHU JALAN LAPIS PONDASI BAWAH KIRI LUAR
        $bahu_jalan_lapis_pondasi_bawah_kiri_luar = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU LUAR')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //BAHU JALAN LAPIS PONDASI BAWAH KANAN LUAR
        $bahu_jalan_lapis_pondasi_bawah_kanan_luar = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU LUAR')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        //BAHU JALAN LAPIS PONDASI BAWAH KIRI DALAM
        $bahu_jalan_lapis_pondasi_bawah_kiri_dalam = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU DALAM')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //BAHU JALAN LAPIS PONDASI BAWAH KANAN DALAM
        $bahu_jalan_lapis_pondasi_bawah_kanan_dalam = DB::table('spatial_lapis_pondasi_bawah_polygon')
            ->selectRaw('spatial_lapis_pondasi_bawah_polygon.tebal, spatial_lapis_pondasi_bawah_polygon.jenis')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_konstruksi_polygon', DB::raw('ST_Intersects(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_polygon.geom::geometry, spatial_lapis_pondasi_bawah_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU DALAM')
            ->where('spatial_segmen_perlengkapan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'lapis_permukaan' => [
                'kiri_luar' => $bahu_jalan_lapis_permukaan_kiri_luar,
                'kanan_luar' => $bahu_jalan_lapis_permukaan_kanan_luar,
                'kiri_dalam' => $bahu_jalan_lapis_permukaan_kiri_dalam,
                'kanan_dalam' => $bahu_jalan_lapis_permukaan_kanan_dalam
            ],
            'lapis_pondasi_atas' => [
                'kiri_luar' => $bahu_jalan_lapis_pondasi_atas_kiri_luar,
                'kanan_luar' => $bahu_jalan_lapis_pondasi_atas_kanan_luar,
                'kiri_dalam' => $bahu_jalan_lapis_pondasi_atas_kiri_dalam,
                'kanan_dalam' => $bahu_jalan_lapis_pondasi_atas_kanan_dalam
            ],
            'lapis_pondasi_bawah' => [
                'kiri_luar' => $bahu_jalan_lapis_pondasi_bawah_kiri_luar,
                'kanan_luar' => $bahu_jalan_lapis_pondasi_bawah_kanan_luar,
                'kiri_dalam' => $bahu_jalan_lapis_pondasi_bawah_kiri_dalam,
                'kanan_dalam' => $bahu_jalan_lapis_pondasi_bawah_kanan_dalam
            ]
        ];
    }

    public function getAdministratif(Request $request)
    {
        $administratif = DB::table('spatial_administratif_polygon')
            ->selectRaw('spatial_administratif_polygon.kode_prov, spatial_administratif_polygon.nama_prov, spatial_administratif_polygon.kode_kab, spatial_administratif_polygon.nama_kab, spatial_administratif_polygon.kode_kec, spatial_administratif_polygon.nama_kec, spatial_administratif_polygon.kode_desa, spatial_administratif_polygon.nama_desa')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_administratif_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->get();

        return $administratif;
    }

    public function getRumahKabel(Request $request)
    {
        //count
        $rumah_kabel_count = DB::table('spatial_rumah_kabel_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rumah_kabel_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->get();

        return $rumah_kabel_count;
    }

    public function getBronjong(Request $request)
    {
        //count
        $bronjong_count = DB::table('spatial_bronjong_line')
            ->selectRaw('spatial_bronjong_line.jenis_material, spatial_bronjong_line.ukuran_panjang, spatial_bronjong_line.kondisi')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_bronjong_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->get();

        return $bronjong_count;
    }

    public function getJembatan(Request $request)
    {
        $jembatan = DB::table('spatial_jembatan_point as jembatan')
            ->selectRaw('jembatan.km, jembatan.panjang, jembatan.lebar, jembatan.luas, jembatan.absis_x, jembatan.ordinat_y')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, jembatan.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->get();

        return $jembatan;
    }

    public function getTiangListrik(Request $request)
    {
        //count
        $tiang_listrik_count = DB::table('spatial_tiang_listrik_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_tiang_listrik_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->get();

        return $tiang_listrik_count;
    }

    public function getBoxCulvert(Request $request)
    {
        //count
        $box_culvert_count = DB::table('spatial_box_culvert_line')
            ->selectRaw('spatial_box_culvert_line.panjang, spatial_box_culvert_line.lebar, spatial_box_culvert_line.tinggi')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_box_culvert_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->get();

        return $box_culvert_count;
    }

    public function getLuasRumija(Request $request)
    {
        //count
        $luas_rumija = DB::table('spatial_ruwasja_polygon')
            ->selectRaw('sum(ST_Area(ST_Intersection(spatial_ruwasja_polygon.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as luas')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_ruwasja_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->first();

        return $luas_rumija;
    }

    public function getLuasBadanJalan(Request $request)
    {
        //HITUNG yang memiliki nilai bagian_jalan: lajur 1/lajur 2/lajur tambahan
        $luas_badan_jalan = DB::table('spatial_segmen_konstruksi_polygon')
            ->selectRaw('sum(ST_Area(ST_Intersection(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as luas')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_segmen_konstruksi_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 1')
            // ->Where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR 2')
            // ->orWhere('spatial_segmen_konstruksi_polygon.bagian_jalan', 'LAJUR TAMBAHAN')
            ->first();
        return $luas_badan_jalan;
    }

    public function getLuasBahuJalan(Request $request)
    {
        $luas_bahu_jalan = DB::table('spatial_segmen_konstruksi_polygon')
            ->selectRaw('sum(ST_Area(ST_Intersection(spatial_segmen_konstruksi_polygon.geom::geometry, spatial_segmen_leger_polygon.geom::geometry)::geography)) as luas')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_segmen_konstruksi_polygon.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU LUAR')
            // ->orWhere('spatial_segmen_konstruksi_polygon.bagian_jalan', 'BAHU DALAM')
            ->first();

        return $luas_bahu_jalan;
    }

    public function getRambuPeringatan(Request $request)
    {
        //KIRI
        $rambu_peringatan_kiri = DB::table('spatial_rambu_peringatan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_peringatan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_peringatan_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $rambu_peringatan_median = DB::table('spatial_rambu_peringatan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_peringatan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_peringatan_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $rambu_peringatan_kanan = DB::table('spatial_rambu_peringatan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_peringatan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_peringatan_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $rambu_peringatan_kiri,
            'median' => $rambu_peringatan_median,
            'kanan' => $rambu_peringatan_kanan
        ];
    }

    public function getRambuLarangan(Request $request)
    {
        //KIRI
        $rambu_larangan_kiri = DB::table('spatial_rambu_larangan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_larangan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_larangan_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $rambu_larangan_median = DB::table('spatial_rambu_larangan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_larangan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_larangan_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $rambu_larangan_kanan = DB::table('spatial_rambu_larangan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_larangan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_larangan_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $rambu_larangan_kiri,
            'median' => $rambu_larangan_median,
            'kanan' => $rambu_larangan_kanan
        ];
    }

    public function getRambuPerintah(Request $request)
    {
        //KIRI
        $rambu_perintah_kiri = DB::table('spatial_rambu_perintah_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_perintah_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_perintah_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $rambu_perintah_median = DB::table('spatial_rambu_perintah_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_perintah_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_perintah_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $rambu_perintah_kanan = DB::table('spatial_rambu_perintah_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_perintah_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_perintah_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $rambu_perintah_kiri,
            'median' => $rambu_perintah_median,
            'kanan' => $rambu_perintah_kanan
        ];
    }

    public function getRambuPetunjuk(Request $request)
    {
        //KIRI
        $rambu_petunjuk_kiri = DB::table('spatial_rambu_petunjuk_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_petunjuk_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_petunjuk_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $rambu_petunjuk_median = DB::table('spatial_rambu_petunjuk_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_petunjuk_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_petunjuk_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $rambu_petunjuk_kanan = DB::table('spatial_rambu_petunjuk_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_petunjuk_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_petunjuk_point.jalur', 'JALUR KANAN')
            ->get();
    }

    public function getRambuElektronik(Request $request)
    {
        //KIRI
        $rambu_elektronik_kiri = DB::table('spatial_rambu_elektronik_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_elektronik_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_elektronik_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $rambu_elektronik_median = DB::table('spatial_rambu_elektronik_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_elektronik_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_elektronik_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $rambu_elektronik_kanan = DB::table('spatial_rambu_elektronik_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_rambu_elektronik_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_rambu_elektronik_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $rambu_elektronik_kiri,
            'median' => $rambu_elektronik_median,
            'kanan' => $rambu_elektronik_kanan
        ];
    }

    public function getMarkaMembujur(Request $request)
    {
        //KIRI
        $marka_membujur_kiri = DB::table('spatial_marka_membujur_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_membujur_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_membujur_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $marka_membujur_median = DB::table('spatial_marka_membujur_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_membujur_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_membujur_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $marka_membujur_kanan = DB::table('spatial_marka_membujur_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_membujur_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_membujur_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $marka_membujur_kiri,
            'median' => $marka_membujur_median,
            'kanan' => $marka_membujur_kanan
        ];
    }

    public function getMarkaMelintang(Request $request)
    {
        //KIRI
        $marka_melintang_kiri = DB::table('spatial_marka_melintang_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_melintang_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_melintang_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $marka_melintang_median = DB::table('spatial_marka_melintang_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_melintang_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_melintang_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $marka_melintang_kanan = DB::table('spatial_marka_melintang_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_melintang_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_melintang_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $marka_melintang_kiri,
            'median' => $marka_melintang_median,
            'kanan' => $marka_melintang_kanan
        ];
    }

    public function getMarkaSerong(Request $request)
    {
        //KIRI
        $marka_serong_kiri = DB::table('spatial_marka_serong_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_serong_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_serong_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $marka_serong_median = DB::table('spatial_marka_serong_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_serong_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_serong_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $marka_serong_kanan = DB::table('spatial_marka_serong_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_serong_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_serong_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $marka_serong_kiri,
            'median' => $marka_serong_median,
            'kanan' => $marka_serong_kanan
        ];
    }

    public function getMarkaKotakKuning(Request $request)
    {
        //KIRI
        $marka_kotak_kuning_kiri = DB::table('spatial_marka_kotak_kuning_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_kotak_kuning_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_kotak_kuning_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $marka_kotak_kuning_median = DB::table('spatial_marka_kotak_kuning_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_kotak_kuning_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_kotak_kuning_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $marka_kotak_kuning_kanan = DB::table('spatial_marka_kotak_kuning_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_kotak_kuning_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_kotak_kuning_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $marka_kotak_kuning_kiri,
            'median' => $marka_kotak_kuning_median,
            'kanan' => $marka_kotak_kuning_kanan
        ];
    }

    public function getMarkaLainnya(Request $request)
    {
        //KIRI
        $marka_lainnya_kiri = DB::table('spatial_marka_lainnya_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_lainnya_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_lainnya_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $marka_lainnya_median = DB::table('spatial_marka_lainnya_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_lainnya_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_lainnya_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $marka_lainnya_kanan = DB::table('spatial_marka_lainnya_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_marka_lainnya_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_marka_lainnya_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $marka_lainnya_kiri,
            'median' => $marka_lainnya_median,
            'kanan' => $marka_lainnya_kanan
        ];
    }

    public function getPakuJalan(Request $request)
    {
        //KIRI
        $paku_jalan_kiri = DB::table('spatial_paku_jalan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_paku_jalan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_paku_jalan_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $paku_jalan_median = DB::table('spatial_paku_jalan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_paku_jalan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_paku_jalan_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $paku_jalan_kanan = DB::table('spatial_paku_jalan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_paku_jalan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_paku_jalan_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $paku_jalan_kiri,
            'median' => $paku_jalan_median,
            'kanan' => $paku_jalan_kanan
        ];
    }

    public function getConcreteBarrier(Request $request)
    {
        //KIRI
        $concrete_barrier_kiri = DB::table('spatial_concrete_barrier_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_concrete_barrier_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_concrete_barrier_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $concrete_barrier_median = DB::table('spatial_concrete_barrier_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_concrete_barrier_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_concrete_barrier_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $concrete_barrier_kanan = DB::table('spatial_concrete_barrier_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_concrete_barrier_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_concrete_barrier_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $concrete_barrier_kiri,
            'median' => $concrete_barrier_median,
            'kanan' => $concrete_barrier_kanan
        ];
    }

    public function getLampuPJU(Request $request)
    {
        //KIRI
        $lampu_pju_kiri = DB::table('spatial_lampu_pju_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_pju_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_pju_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $lampu_pju_median = DB::table('spatial_lampu_pju_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_pju_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_pju_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $lampu_pju_kanan = DB::table('spatial_lampu_pju_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_pju_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_pju_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $lampu_pju_kiri,
            'median' => $lampu_pju_median,
            'kanan' => $lampu_pju_kanan
        ];
    }

    public function getHighmastTower(Request $request)
    {
        //KIRI
        $highmast_tower_kiri = DB::table('spatial_highmast_tower_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_highmast_tower_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_highmast_tower_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $highmast_tower_median = DB::table('spatial_highmast_tower_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_highmast_tower_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_highmast_tower_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $highmast_tower_kanan = DB::table('spatial_highmast_tower_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_highmast_tower_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_highmast_tower_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $highmast_tower_kiri,
            'median' => $highmast_tower_median,
            'kanan' => $highmast_tower_kanan
        ];
    }

    public function getLampuSatuWarna(Request $request)
    {
        //KIRI
        $lampu_satu_warna_kiri = DB::table('spatial_lampu_satu_warna_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_satu_warna_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_satu_warna_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $lampu_satu_warna_median = DB::table('spatial_lampu_satu_warna_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_satu_warna_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_satu_warna_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $lampu_satu_warna_kanan = DB::table('spatial_lampu_satu_warna_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_satu_warna_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_satu_warna_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $lampu_satu_warna_kiri,
            'median' => $lampu_satu_warna_median,
            'kanan' => $lampu_satu_warna_kanan
        ];
    }

    public function getLampuDuaWarna(Request $request)
    {
        //KIRI
        $lampu_dua_warna_kiri = DB::table('spatial_lampu_dua_warna_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_dua_warna_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_dua_warna_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $lampu_dua_warna_median = DB::table('spatial_lampu_dua_warna_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_dua_warna_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_dua_warna_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $lampu_dua_warna_kanan = DB::table('spatial_lampu_dua_warna_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_dua_warna_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_dua_warna_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $lampu_dua_warna_kiri,
            'median' => $lampu_dua_warna_median,
            'kanan' => $lampu_dua_warna_kanan
        ];
    }

    public function getLampuTigaWarna(Request $request)
    {
        //KIRI
        $lampu_tiga_warna_kiri = DB::table('spatial_lampu_tiga_warna_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_tiga_warna_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_tiga_warna_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $lampu_tiga_warna_median = DB::table('spatial_lampu_tiga_warna_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_tiga_warna_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_tiga_warna_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $lampu_tiga_warna_kanan = DB::table('spatial_lampu_tiga_warna_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_lampu_tiga_warna_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_lampu_tiga_warna_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $lampu_tiga_warna_kiri,
            'median' => $lampu_tiga_warna_median,
            'kanan' => $lampu_tiga_warna_kanan
        ];
    }

    public function getPagarPengamanKaku(Request $request)
    {
        //KIRI
        $pagar_pengaman_kaku_kiri = DB::table('spatial_pagar_pengaman_kaku_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_pengaman_kaku_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_pagar_pengaman_kaku_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $pagar_pengaman_kaku_median = DB::table('spatial_pagar_pengaman_kaku_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_pengaman_kaku_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_pagar_pengaman_kaku_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $pagar_pengaman_kaku_kanan = DB::table('spatial_pagar_pengaman_kaku_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_pengaman_kaku_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_pagar_pengaman_kaku_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $pagar_pengaman_kaku_kiri,
            'median' => $pagar_pengaman_kaku_median,
            'kanan' => $pagar_pengaman_kaku_kanan
        ];
    }

    public function getPagarPengamanSemiKaku(Request $request)
    {
        //KIRI
        $pagar_pengaman_semi_kaku_kiri = DB::table('spatial_pagar_pengaman_semi_kaku_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_pengaman_semi_kaku_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_pagar_pengaman_semi_kaku_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $pagar_pengaman_semi_kaku_median = DB::table('spatial_pagar_pengaman_semi_kaku_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_pengaman_semi_kaku_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_pagar_pengaman_semi_kaku_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $pagar_pengaman_semi_kaku_kanan = DB::table('spatial_pagar_pengaman_semi_kaku_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_pengaman_semi_kaku_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_pagar_pengaman_semi_kaku_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $pagar_pengaman_semi_kaku_kiri,
            'median' => $pagar_pengaman_semi_kaku_median,
            'kanan' => $pagar_pengaman_semi_kaku_kanan
        ];
    }

    public function getPagarPengamanFleksibel(Request $request)
    {
        //KIRI
        $pagar_pengaman_fleksibel_kiri = DB::table('spatial_pagar_pengaman_fleksibel_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_pengaman_fleksibel_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_pagar_pengaman_fleksibel_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $pagar_pengaman_fleksibel_median = DB::table('spatial_pagar_pengaman_fleksibel_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_pengaman_fleksibel_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_pagar_pengaman_fleksibel_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $pagar_pengaman_fleksibel_kanan = DB::table('spatial_pagar_pengaman_fleksibel_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pagar_pengaman_fleksibel_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_pagar_pengaman_fleksibel_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $pagar_pengaman_fleksibel_kiri,
            'median' => $pagar_pengaman_fleksibel_median,
            'kanan' => $pagar_pengaman_fleksibel_kanan
        ];
    }

    public function getCrashCushion(Request $request)
    {
        //KIRI
        $crash_cushion_kiri = DB::table('spatial_crash_cushion_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_crash_cushion_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_crash_cushion_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $crash_cushion_median = DB::table('spatial_crash_cushion_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_crash_cushion_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_crash_cushion_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $crash_cushion_kanan = DB::table('spatial_crash_cushion_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_crash_cushion_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_crash_cushion_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $crash_cushion_kiri,
            'median' => $crash_cushion_median,
            'kanan' => $crash_cushion_kanan
        ];
    }

    public function getSafetyRoller(Request $request)
    {
        //KIRI
        $safety_roller_kiri = DB::table('spatial_safety_roller_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_safety_roller_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_safety_roller_line.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $safety_roller_median = DB::table('spatial_safety_roller_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_safety_roller_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_safety_roller_line.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $safety_roller_kanan = DB::table('spatial_safety_roller_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_safety_roller_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_safety_roller_line.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $safety_roller_kiri,
            'median' => $safety_roller_median,
            'kanan' => $safety_roller_kanan
        ];
    }

    public function getCerminTikungan(Request $request)
    {
        //KIRI
        $cermin_tikungan_kiri = DB::table('spatial_cermin_tikungan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_cermin_tikungan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_cermin_tikungan_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $cermin_tikungan_median = DB::table('spatial_cermin_tikungan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_cermin_tikungan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_cermin_tikungan_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $cermin_tikungan_kanan = DB::table('spatial_cermin_tikungan_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_cermin_tikungan_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_cermin_tikungan_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $cermin_tikungan_kiri,
            'median' => $cermin_tikungan_median,
            'kanan' => $cermin_tikungan_kanan
        ];
    }

    public function getPatokLaluLintas(Request $request)
    {
        //KIRI
        $patok_lalu_lintas_kiri = DB::table('spatial_patok_lalu_lintas_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_lalu_lintas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_patok_lalu_lintas_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $patok_lalu_lintas_median = DB::table('spatial_patok_lalu_lintas_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_lalu_lintas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_patok_lalu_lintas_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $patok_lalu_lintas_kanan = DB::table('spatial_patok_lalu_lintas_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_lalu_lintas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_patok_lalu_lintas_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $patok_lalu_lintas_kiri,
            'median' => $patok_lalu_lintas_median,
            'kanan' => $patok_lalu_lintas_kanan
        ];
    }

    public function getReflektor(Request $request)
    {
        //KIRI
        $reflektor_kiri = DB::table('spatial_reflektor_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_reflektor_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_reflektor_point.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $reflektor_median = DB::table('spatial_reflektor_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_reflektor_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_reflektor_point.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $reflektor_kanan = DB::table('spatial_reflektor_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_reflektor_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_reflektor_point.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $reflektor_kiri,
            'median' => $reflektor_median,
            'kanan' => $reflektor_kanan
        ];
    }

    public function getPitaPenggaduh(Request $request)
    {
        //KIRI
        $pita_penggaduh_kiri = DB::table('spatial_pita_kejut_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_pita_kejut_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pita_kejut_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $pita_penggaduh_median = DB::table('spatial_pita_kejut_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_pita_kejut_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pita_kejut_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $pita_penggaduh_kanan = DB::table('spatial_pita_kejut_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_pita_kejut_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pita_kejut_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $pita_penggaduh_kiri,
            'median' => $pita_penggaduh_median,
            'kanan' => $pita_penggaduh_kanan
        ];
    }

    public function getJalurPenghentianDarurat(Request $request)
    {
        //KIRI
        $jalur_penghentian_darurat_kiri = DB::table('spatial_jalur_penghentian_darurat_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_jalur_penghentian_darurat_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_jalur_penghentian_darurat_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $jalur_penghentian_darurat_median = DB::table('spatial_jalur_penghentian_darurat_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_jalur_penghentian_darurat_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_jalur_penghentian_darurat_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $jalur_penghentian_darurat_kanan = DB::table('spatial_jalur_penghentian_darurat_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_jalur_penghentian_darurat_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_jalur_penghentian_darurat_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $jalur_penghentian_darurat_kiri,
            'median' => $jalur_penghentian_darurat_median,
            'kanan' => $jalur_penghentian_darurat_kanan
        ];
    }

    public function getPembatasKecepatan(Request $request)
    {
        //KIRI
        $pembatas_kecepatan_kiri = DB::table('spatial_pembatas_kecepatan_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pembatas_kecepatan_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pembatas_kecepatan_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $pembatas_kecepatan_median = DB::table('spatial_pembatas_kecepatan_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pembatas_kecepatan_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pembatas_kecepatan_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $pembatas_kecepatan_kanan = DB::table('spatial_pembatas_kecepatan_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pembatas_kecepatan_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pembatas_kecepatan_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $pembatas_kecepatan_kiri,
            'median' => $pembatas_kecepatan_median,
            'kanan' => $pembatas_kecepatan_kanan
        ];
    }

    public function getPembatasTinggidanLebar(Request $request)
    {
        //KIRI
        $pembatas_tinggi_dan_lebar_kiri = DB::table('spatial_pembatas_tinggi_dan_lebar_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pembatas_tinggi_dan_lebar_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pembatas_tinggi_dan_lebar_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $pembatas_tinggi_dan_lebar_median = DB::table('spatial_pembatas_tinggi_dan_lebar_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pembatas_tinggi_dan_lebar_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pembatas_tinggi_dan_lebar_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $pembatas_tinggi_dan_lebar_kanan = DB::table('spatial_pembatas_tinggi_dan_lebar_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_pembatas_tinggi_dan_lebar_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pembatas_tinggi_dan_lebar_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $pembatas_tinggi_dan_lebar_kiri,
            'median' => $pembatas_tinggi_dan_lebar_median,
            'kanan' => $pembatas_tinggi_dan_lebar_kanan
        ];
    }

    public function getPenahanSilau(Request $request)
    {
        //KIRI
        $penahan_silau_kiri = DB::table('spatial_penahan_silau_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_penahan_silau_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_penahan_silau_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $penahan_silau_median = DB::table('spatial_penahan_silau_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_penahan_silau_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_penahan_silau_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $penahan_silau_kanan = DB::table('spatial_penahan_silau_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_penahan_silau_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkan_jalan_polygon.geom::geometry, spatial_penahan_silau_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $penahan_silau_kiri,
            'median' => $penahan_silau_median,
            'kanan' => $penahan_silau_kanan
        ];
    }

    public function getPeredamBising(Request $request)
    {
        //KIRI
        $peredam_bising_kiri = DB::table('spatial_peredam_bising_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_peredam_bising_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_peredam_bising_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $peredam_bising_median = DB::table('spatial_peredam_bising_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_peredam_bising_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_peredam_bising_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $peredam_bising_kanan = DB::table('spatial_peredam_bising_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_peredam_bising_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_peredam_bising_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $peredam_bising_kiri,
            'median' => $peredam_bising_median,
            'kanan' => $peredam_bising_kanan
        ];
    }

    public function getKameraPengawas(Request $request)
    {
        //KIRI
        $kamera_pengawas_kiri = DB::table('spatial_kamera_pengawas_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_kamera_pengawas_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_kamera_pengawas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $kamera_pengawas_median = DB::table('spatial_kamera_pengawas_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_kamera_pengawas_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_kamera_pengawas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $kamera_pengawas_kanan = DB::table('spatial_kamera_pengawas_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_kamera_pengawas_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_kamera_pengawas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $kamera_pengawas_kiri,
            'median' => $kamera_pengawas_median,
            'kanan' => $kamera_pengawas_kanan
        ];
    }

    public function getSpeedgun(Request $request)
    {
        //KIRI
        $speedgun_kiri = DB::table('spatial_speedgun_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_speedgun_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_speedgun_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $speedgun_median = DB::table('spatial_speedgun_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_speedgun_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_speedgun_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $speedgun_kanan = DB::table('spatial_speedgun_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_speedgun_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_speedgun_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $speedgun_kiri,
            'median' => $speedgun_median,
            'kanan' => $speedgun_kanan
        ];
    }

    public function getPengamanSaluranUdaraTeganganTinggi(Request $request)
    {
        //KIRI
        $pengaman_saluran_udara_tegangan_tinggi_kiri = DB::table('spatial_pengaman_saluran_udara_tegangan_tinggi_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_pengaman_saluran_udara_tegangan_tinggi_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pengaman_saluran_udara_tegangan_tinggi_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $pengaman_saluran_udara_tegangan_tinggi_median = DB::table('spatial_pengaman_saluran_udara_tegangan_tinggi_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_pengaman_saluran_udara_tegangan_tinggi_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pengaman_saluran_udara_tegangan_tinggi_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $pengaman_saluran_udara_tegangan_tinggi_kanan = DB::table('spatial_pengaman_saluran_udara_tegangan_tinggi_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_pengaman_saluran_udara_tegangan_tinggi_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_pengaman_saluran_udara_tegangan_tinggi_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $pengaman_saluran_udara_tegangan_tinggi_kiri,
            'median' => $pengaman_saluran_udara_tegangan_tinggi_median,
            'kanan' => $pengaman_saluran_udara_tegangan_tinggi_kanan
        ];
    }

    public function getPatokUtilitas(Request $request)
    {
        //KIRI
        $patok_utilitas_kiri = DB::table('spatial_patok_utilitas_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_utilitas_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_patok_utilitas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $patok_utilitas_median = DB::table('spatial_patok_utilitas_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_utilitas_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_patok_utilitas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $patok_utilitas_kanan = DB::table('spatial_patok_utilitas_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_patok_utilitas_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_patok_utilitas_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $patok_utilitas_kiri,
            'median' => $patok_utilitas_median,
            'kanan' => $patok_utilitas_kanan
        ];
    }

    public function getPapanPengumumanKepemilikanTanahNegara(Request $request)
    {
        //KIRI
        $papan_pengumuman_kepemilikan_tanah_negara_kiri = DB::table('spatial_papan_pengumuman_kepemilikan_tanah_negara_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_papan_pengumuman_kepemilikan_tanah_negara_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_papan_pengumuman_kepemilikan_tanah_negara_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $papan_pengumuman_kepemilikan_tanah_negara_median = DB::table('spatial_papan_pengumuman_kepemilikan_tanah_negara_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_papan_pengumuman_kepemilikan_tanah_negara_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_papan_pengumuman_kepemilikan_tanah_negara_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $papan_pengumuman_kepemilikan_tanah_negara_kanan = DB::table('spatial_papan_pengumuman_kepemilikan_tanah_negara_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_papan_pengumuman_kepemilikan_tanah_negara_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_papan_pengumuman_kepemilikan_tanah_negara_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $papan_pengumuman_kepemilikan_tanah_negara_kiri,
            'median' => $papan_pengumuman_kepemilikan_tanah_negara_median,
            'kanan' => $papan_pengumuman_kepemilikan_tanah_negara_kanan
        ];
    }

    public function getReklame(Request $request)
    {
        //KIRI
        $reklame_kiri = DB::table('spatial_reklame_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_reklame_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_reklame_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $reklame_median = DB::table('spatial_reklame_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_reklame_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_reklame_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $reklame_kanan = DB::table('spatial_reklame_point')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Contains(spatial_segmen_leger_polygon.geom::geometry, spatial_reklame_point.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Contains(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_reklame_point.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $reklame_kiri,
            'median' => $reklame_median,
            'kanan' => $reklame_kanan
        ];
    }

    public function getFasilitasPutarBalik(Request $request)
    {
        //KIRI
        $fasilitas_putar_balik_kiri = DB::table('spatial_fasilitas_putar_balik_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_fasilitas_putar_balik_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_fasilitas_putar_balik_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KIRI')
            ->get();

        //MEDIAN
        $fasilitas_putar_balik_median = DB::table('spatial_fasilitas_putar_balik_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_fasilitas_putar_balik_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_fasilitas_putar_balik_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'MEDIAN')
            ->get();

        //KANAN
        $fasilitas_putar_balik_kanan = DB::table('spatial_fasilitas_putar_balik_line')
            ->selectRaw('count(*) as count')
            ->join('spatial_segmen_leger_polygon', DB::raw('ST_Intersects(spatial_segmen_leger_polygon.geom::geometry, spatial_fasilitas_putar_balik_line.geom::geometry)'), '=', DB::raw('true'))
            ->join('spatial_segmen_perlengkapan_jalan_polygon', DB::raw('ST_Intersects(spatial_segmen_perlengkapan_jalan_polygon.geom::geometry, spatial_fasilitas_putar_balik_line.geom::geometry)'), '=', DB::raw('true'))
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->where('spatial_segmen_perlengkapan_jalan_polygon.jalur', 'JALUR KANAN')
            ->get();

        return [
            'kiri' => $fasilitas_putar_balik_kiri,
            'median' => $fasilitas_putar_balik_median,
            'kanan' => $fasilitas_putar_balik_kanan
        ];
    }

    public function getTitikSegmen(Request $request)
    {
        //TITIK AWAL
        $titik_awal_segmen_ruas_jalan = DB::table('spatial_segmen_leger_polygon')
            ->where('spatial_segmen_leger_polygon.id_leger', $request->leger_id)
            ->selectRaw('spatial_segmen_leger_polygon.km as km')
            ->first();

        // Mengambil ID segmen selanjutnya dengan mengubah penomoran (contoh: dari M.001 ke M.002)
        $leger_id_selanjutnya = 'M.' . str_pad((int) substr($request->leger_id, 2) + 1, 3, '0', STR_PAD_LEFT);

        // TITIK AKHIR
        $titik_akhir_segmen_ruas_jalan = DB::table('spatial_segmen_leger_polygon')
            ->where('spatial_segmen_leger_polygon.id_leger', $leger_id_selanjutnya)
            ->selectRaw('spatial_segmen_leger_polygon.km as km')
            ->first(); // Mengambil satu baris saja karena titik akhir adalah satu segmen

        $titik_akhir_segmen_akhir = DB::table('spatial_segmen_seksi_polygon')
        ->where('spatial_segmen_seksi_polygon.jalan_tol_id', 1)
        ->selectRaw('spatial_segmen_seksi_polygon.km_akhir as km')
        ->first(); // Mengambil satu baris saja karena titik akhir adalah satu segmen

        //TITIK IKAT PATOK
    // Mengambil titik awal dan akhir km dari segmen ruas jalan
    $titik_awal_km = (float) str_replace('+', '.', $titik_awal_segmen_ruas_jalan->km);

    if ($titik_akhir_segmen_ruas_jalan)
    {
        $titik_akhir_km = (float) str_replace('+', '.', $titik_akhir_segmen_ruas_jalan->km);
    }
    else
    {
        $titik_akhir_km = (float) str_replace('+', '.', $titik_akhir_segmen_akhir->km);
    }


    // Mengambil patok yang km-nya berada di antara titik awal dan akhir
    $titik_ikat_patok_km = DB::table('spatial_patok_km_point')
        ->whereRaw("CAST(REPLACE(km, '+', '.') AS FLOAT) BETWEEN ? AND ?", [$titik_awal_km, $titik_akhir_km])
        ->where('km','like','%+000')
        ->selectRaw('km, ST_X(geom::geometry) as x, ST_Y(geom::geometry) as y, ST_Z(geom::geometry) as z')
        ->first();

        // Menggabungkan hasil
        return [
            'titik_awal_segmen' => $titik_awal_segmen_ruas_jalan ?? null,
            'titik_akhir_segmen' => $titik_akhir_segmen_ruas_jalan ?? $titik_akhir_segmen_akhir ?? null,
            'titik_ikat_patok_km' => $titik_ikat_patok_km ?? null,
        ];
    }

    public function getDataJalanUtama(Request $request)
    {
        //init
        $data = [];

        $data = [
            'rambu_lalulintas_count' => $this->getRambuLaluLintas($request),
            'gorong_gorong' => $this->getGorongGorong($request),
            'data_geometrik_jalan' => $this->getDataGeometrikJalan($request),
            'patok_km_count' => $this->getPatokKM($request),
            'patok_hm_count' => $this->getPatokHM($request),
            'patok_lj_count' => $this->getPatokLJ($request),
            'patok_rmj_count' => $this->getPatokRMJ($request),
            'lhr_sum' => $this->getLHR($request),
            'listrik_bawah_tanah' => $this->getListrikBawahTanah($request),
            'manhole' => $this->getManhole($request),
            'badan_jalan' => $this->getBadanJalan($request),
            'median' => $this->getMedian($request),
            'bahu_jalan' => $this->getBahuJalan($request),
            'pagar_operasional' => $this->getPagarOperasional($request),
            'marka_jalan' => $this->getMarkaJalan($request),
            'titik_segmen' => $this->getTitikSegmen($request),
            'bronjong' => $this->getBronjong($request),
            'luas_rumija' => $this->getLuasRumija($request),
            'luas_badan_jalan' => $this->getLuasBadanJalan($request),
            'luas_bahu_jalan' => $this->getLuasBahuJalan($request),
            'administratif' => $this->getAdministratif($request),
            'rumah_kabel' => $this->getRumahKabel($request),  
            'telepon_bawah_tanah' => $this->getTeleponBawahTanah($request),
            'jembatan' => $this->getJembatan($request),
            'tiang_listrik' => $this->getTiangListrik($request),
        ];

        return response()->json($data);
    }

    public function getDataJalanUtamaAll()
    {
        //get all leger id that starts with "M"
        $leger = DB::table('spatial_segmen_leger_polygon')
            ->select('id_leger')
            ->where('id_leger', 'like', 'M%')
            ->orderBy('id_leger', 'asc') // Sort in ascending order
            ->get();

        //init
        $data = [];
        foreach ($leger as $l) {
            $request = new Request();
            $request->merge(['leger_id' => $l->id_leger]);

            $data[$l->id_leger] = [
                'rambu_lalulintas_count' => $this->getRambuLaluLintas($request),
                'gorong_gorong' => $this->getGorongGorong($request),
                'data_geometrik_jalan' => $this->getDataGeometrikJalan($request),
                'patok_km_count' => $this->getPatokKM($request),
                'patok_hm_count' => $this->getPatokHM($request),
                'patok_lj_count' => $this->getPatokLJ($request),
                'patok_rmj_count' => $this->getPatokRMJ($request),
                'lhr_sum' => $this->getLHR($request),
                'listrik_bawah_tanah' => $this->getListrikBawahTanah($request),
                'manhole' => $this->getManhole($request),
                'badan_jalan' => $this->getBadanJalan($request),
                'median' => $this->getMedian($request),
                'bahu_jalan' => $this->getBahuJalan($request),
                'pagar_operasional' => $this->getPagarOperasional($request),
                'marka_jalan' => $this->getMarkaJalan($request),
                'titik_segmen' => $this->getTitikSegmen($request),
                'bronjong' => $this->getBronjong($request),
                'luas_rumija' => $this->getLuasRumija($request),
                'luas_badan_jalan' => $this->getLuasBadanJalan($request),
                'luas_bahu_jalan' => $this->getLuasBahuJalan($request),
                'administratif' => $this->getAdministratif($request),
                'rumah_kabel' => $this->getRumahKabel($request),  
                'telepon_bawah_tanah' => $this->getTeleponBawahTanah($request),
                'jembatan' => $this->getJembatan($request),
                'tiang_listrik' => $this->getTiangListrik($request),

            ];
        }

        return response()->json($data);
    }
}
