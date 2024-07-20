<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Resources\GeoJSONResource;

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

class OutputController extends Controller
{
    public function getAset($type)
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
            return $this->{$getFunctions[$type]}();
        } else {
            abort(404, 'Tipe Aset tidak ditemukan');
        }
    }

    public function getAdministratifPolygon()
    {
        $data = AdministratifPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom::geometry, 4326)) AS geojson")->get()->makeHidden('geom');
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
