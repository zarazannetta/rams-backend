<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\GeoJSONResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\JalanTol;
use App\Models\Spatial\{
    AdministratifPolygon,
    BatasDesaLine,
    BoxCulvertLine,
    BPTLine,
    BronjongLine,
    ConcreteBarrierLine,
    DataGeometrikJalanPolygon,
    GerbangLine,
    GerbangPoint,
    GorongGorongLine,
    GuardRailLine,
    IRIPolygon,
    JalanLine,
    JembatanPoint,
    JembatanPolygon,
    LampuLalulintasPoint,
    LapisPermukaanPolygon,
    LapisPondasiAtas1Polygon,
    LapisPondasiAtas2Polygon,
    LapisPondasiBawahPolygon,
    LHRPolygon,
    ListrikBawahtanahLine,
    ManholePoint,
    MarkaLine,
    PagarOperasionalLine,
    PatokHMPoint,
    PatokKMPoint,
    PatokLJPoint,
    PatokPemanduPoint,
    PatokRMJPoint,
    PatokROWPoint,
    PitaKejutLine,
    RambuLalulintasPoint,
    RambuPenunjukarahPoint,
    ReflektorPoint,
    RiolLine,
    RumahKabelPoint,
    RuwasjaPolygon,
    SaluranLine,
    SegmenKonstruksiPolygon,
    SegmenLegerPolygon,
    SegmenPerlengkapanPolygon,
    SegmenSeksiPolygon,
    SegmenTolPolygon,
    StaTextPoint,
    SungaiLine,
    TeleponBawahtanahLine,
    TiangListrikPoint,
    TiangTeleponPoint,
    VMSPoint
};

class OutputController extends Controller
{
    public function getAset($type, Request $request)
    {
        $getFunctions = [
            'administratif_polygon' => 'getAdministratifPolygon',
            'batas_desa_line' => 'getBatasDesaLine',
            'box_culvert_line' => 'getBoxCulvertLine',
            'bpt_line' => 'getBPTLine',
            'bronjong_line' => 'getBronjongLine',
            'concrete_barrier_line' => 'getConcreteBarrierLine',
            'data_geometrik_jalan_polygon' => 'getDataGeometrikJalanPolygon',
            'gerbang_line' => 'getGerbangLine',
            'gerbang_point' => 'getGerbangPoint',
            'gorong_gorong_line' => 'getGorongGorongLine',
            'guardrail_line' => 'getGuardrailLine',
            'iri_polygon' => 'getIRIPolygon',
            'jalan_line' => 'getJalanLine',
            'jembatan_point' => 'getJembatanPoint',
            'jembatan_polygon' => 'getJembatanPolygon',
            'lampu_lalulintas_point' => 'getLampuLalulintasPoint',
            'lapis_permukaan_polygon' => 'getLapisPermukaanPolygon',
            'lapis_pondasi_atas1_polygon' => 'getLapisPondasiAtas1Polygon',
            'lapis_pondasi_atas2_polygon' => 'getLapisPondasiAtas2Polygon',
            'lapis_pondasi_bawah_polygon' => 'getLapisPondasiBawahPolygon',
            'lhr_polygon' => 'getLHRPolygon',
            'listrik_bawahtanah_line' => 'getListrikBawahtanahLine',
            'manhole_point' => 'getManholePoint',
            'marka_line' => 'getMarkaLine',
            'pagar_operasional_line' => 'getPagarOperasionalLine',
            'patok_hm_point' => 'getPatokHMPoint',
            'patok_km_point' => 'getPatokKMPoint',
            'patok_lj_point' => 'getPatokLJPoint',
            'patok_pemandu_point' => 'getPatokPemanduPoint',
            'patok_rmj_point' => 'getPatokRMJPoint',
            'patok_row_point' => 'getPatokROWPoint',
            'pita_kejut_line' => 'getPitaKejutLine',
            'rambu_lalulintas_point' => 'getRambuLalulintasPoint',
            'rambu_penunjukarah_point' => 'getRambuPenunjukarahPoint',
            'reflektor_point' => 'getReflektorPoint',
            'riol_line' => 'getRiolLine',
            'rumah_kabel_point' => 'getRumahKabelPoint',
            'ruwasja_polygon' => 'getRuwasjaPolygon',
            'saluran_line' => 'getSaluranLine',
            'segmen_konstruksi_polygon' => 'getSegmenKonstruksiPolygon',
            'segmen_leger_polygon' => 'getSegmenLegerPolygon',
            'segmen_perlengkapan_polygon' => 'getSegmenPerlengkapanPolygon',
            'segmen_seksi_polygon' => 'getSegmenSeksiPolygon',
            'segmen_tol_polygon' => 'getSegmenTolPolygon',
            'sta_text_point' => 'getStaTextPoint',
            'sungai_line' => 'getSungaiLine',
            'telepon_bawahtanah_line' => 'getTeleponBawahtanahLine',
            'tiang_listrik_point' => 'getTiangListrikPoint',
            'tiang_telepon_point' => 'getTiangTeleponPoint',
            'vms_point' => 'getVMSPoint',
        ];

        if (array_key_exists($type, $getFunctions)) {
            return $this->{$getFunctions[$type]}($request->query('start_km'), $request->query('end_km'));
        } else {
            abort(404, 'Tipe Aset tidak ditemukan');
        }
    }

    public function getAdministratifPolygon($start_km = null, $end_km = null)
    {
        // $startPoint = DB::table('spatial_patok_km_point')
        //     ->select(DB::raw('ST_X(geom) as x, ST_Y(geom) as y'))
        //     ->where('km', 'LIKE', '%' . $start_km . '%')
        //     ->first();

        // $endPoint = DB::table('spatial_patok_km_point')
        //     ->select(DB::raw('ST_X(geom) as x, ST_Y(geom) as y'))
        //     ->where('km', 'LIKE', '%' . $end_km . '%')
        //     ->first();

        // Normalize km format by removing '+' and any spaces
        $start_km = $start_km ? str_replace(['+', ' '], '', $start_km) : null;
        $end_km = $end_km ? str_replace(['+', ' '], '', $end_km) : null;

        // Query start point and end point
        $startPoint = DB::table('spatial_patok_km_point')
            ->select(DB::raw('ST_X(geom) as x, ST_Y(geom) as y'))
            ->whereRaw("CAST(REPLACE(REPLACE(km, '+', ''), ' ', '') AS INTEGER) = ?", [$start_km])
            ->first();

        $endPoint = DB::table('spatial_patok_km_point')
            ->select(DB::raw('ST_X(geom) as x, ST_Y(geom) as y'))
            ->whereRaw("CAST(REPLACE(REPLACE(km, '+', ''), ' ', '') AS INTEGER) = ?", [$end_km])
            ->first();
            
        if ($start_km && $end_km !== null) {
            if (!$startPoint || !$endPoint) {
                return response()->json([
                    'error' => 'Start or end km point not found',
                    'missing' => [
                        'start_km' => !$startPoint ? $start_km : null,
                        'end_km' => !$endPoint ? $end_km : null,
                    ]], 404);
            } else {
                $data = DB::select("
                    WITH original_points AS (
                        SELECT 
                            ST_SetSRID(ST_MakePoint(:x1, :y1), 4326) AS geom1,
                            ST_SetSRID(ST_MakePoint(:x2, :y2), 4326) AS geom2
                    ),
                    projected_points AS (
                        SELECT 
                            ST_Project(geom1::geography, 1000, radians(270))::geometry AS point_kiri1,
                            ST_Project(geom1::geography, 1000, radians(90))::geometry AS point_kanan1,
                            ST_Project(geom2::geography, 1000, radians(270))::geometry AS point_kiri2,
                            ST_Project(geom2::geography, 1000, radians(90))::geometry AS point_kanan2
                        FROM original_points
                    ),
                    bounding_box AS (
                        SELECT 
                            ST_SetSRID(
                                ST_MakePolygon(
                                    ST_MakeLine(
                                        ARRAY[
                                            (SELECT point_kiri1 FROM projected_points),
                                            (SELECT point_kanan1 FROM projected_points),
                                            (SELECT point_kanan2 FROM projected_points),
                                            (SELECT point_kiri2 FROM projected_points),
                                            (SELECT point_kiri1 FROM projected_points)
                                        ]
                                    )
                                ), 4326
                            ) AS geom
                    )
                    
                    SELECT 
                        ST_AsGeoJSON(ST_Intersection(bb.geom, ap.geom::geometry)) AS geojson
                    FROM 
                        bounding_box bb
                    JOIN 
                        spatial_administratif_polygon ap ON ST_Intersects(bb.geom, ap.geom::geometry);
                ", [
                    'x1' => $startPoint->x,
                    'y1' => $startPoint->y,
                    'x2' => $endPoint->x,
                    'y2' => $endPoint->y,
                ]
            );

                $features = array_map(function ($item) {
                    return [
                        'type' => 'Feature',
                        'geometry' => json_decode($item->geojson),
                        'properties' => new \stdClass(),
                    ];
                }, $data);

                $featureCollection = [
                    "type" => "FeatureCollection",
                    "features" => $features,
                ];

                return response()->json($featureCollection);
            }

        }

    // Default return if no start or end km
        $data = AdministratifPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")
            ->get()
            ->makeHidden('geom');
        
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];

        return response()->json($featureCollection);
    }

    public function getBatasDesaLine()
    {
        $data = BatasDesaLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getBoxCulvertLine()
    {
        $data = BoxCulvertLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getBPTLine()
    {
        $data = BPTLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getBronjongLine()
    {
        $data = BronjongLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getConcreteBarrierLine()
    {
        $data = ConcreteBarrierLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getDataGeometrikJalanPolygon()
    {
        $data = DataGeometrikJalanPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getGerbangLine()
    {
        $data = GerbangLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getGerbangPoint()
    {
        $data = GerbangPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getGorongGorongLine()
    {
        $data = GorongGorongLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getGuardrailLine()
    {
        $data = GuardrailLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getIRIPolygon()
    {
        $data = IRIPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getJalanLine()
    {
        $data = JalanLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getJembatanPoint()
    {
        $data = JembatanPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getJembatanPolygon()
    {
        $data = JembatanPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getLampuLalulintasPoint()
    {
        $data = LampuLalulintasPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getLapisPermukaanPolygon()
    {
        $data = LapisPermukaanPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getLapisPondasiAtas1Polygon()
    {
        $data = LapisPondasiAtas1Polygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getLapisPondasiAtas2Polygon()
    {
        $data = LapisPondasiAtas2Polygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getLapisPondasiBawahPolygon()
    {
        $data = LapisPondasiBawahPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getLHRPolygon()
    {
        $data = LHRPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getListrikBawahtanahLine()
    {
        $data = ListrikBawahtanahLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getManholePoint()
    {
        $data = ManholePoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getMarkaLine()
    {
        $data = MarkaLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getPagarOperasionalLine()
    {
        $data = PagarOperasionalLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getPatokHMPoint()
    {
        $data = PatokHMPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getPatokKMPoint()
    {
        $data = PatokKMPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getPatokLJPoint()
    {
        $data = PatokLJPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getPatokPemanduPoint()
    {
        $data = PatokPemanduPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getPatokRMJPoint()
    {
        $data = PatokRMJPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getPatokROWPoint()
    {
        $data = PatokROWPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getPitaKejutLine()
    {
        $data = PitaKejutLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getRambuLalulintasPoint()
    {
        $data = RambuLalulintasPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getRambuPenunjukarahPoint()
    {
        $data = RambuPenunjukarahPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getReflektorPoint()
    {
        $data = ReflektorPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getRiolLine()
    {
        $data = RiolLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getRumahKabelPoint()
    {
        $data = RumahKabelPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getRuwasjaPolygon()
    {
        $data = RuwasjaPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getSaluranLine()
    {
        $data = SaluranLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getSegmenKonstruksiPolygon()
    {
        $data = SegmenKonstruksiPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getSegmenLegerPolygon()
    {
        $data = SegmenLegerPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getSegmenPerlengkapanPolygon()
    {
        $data = SegmenPerlengkapanPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getSegmenSeksiPolygon()
    {
        $data = SegmenSeksiPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getSegmenTolPolygon()
    {
        $data = SegmenTolPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getStaTextPoint()
    {
        $data = StaTextPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getSungaiLine()
    {
        $data = SungaiLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getTeleponBawahtanahLine()
    {
        $data = TeleponBawahtanahLine::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getTiangListrikPoint()
    {
        $data = TiangListrikPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getTiangTeleponPoint()
    {
        $data = TiangTeleponPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }

    public function getVMSPoint()
    {
        $data = VMSPoint::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
        $features = GeoJSONResource::collection($data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $features,
        ];
        return response()->json($featureCollection);
    }
}
