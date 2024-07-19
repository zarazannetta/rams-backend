<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeoJSONResource;
use App\Models\Spatial\Administratif;
use App\Models\Spatial\GeometrikLingkungan;
use App\Models\Spatial\IRI;
use App\Models\Spatial\Jembatan;
use App\Models\Spatial\JembatanPolygon;
use App\Models\Spatial\LampuLalulintas;
use App\Models\Spatial\LHR;
use App\Models\Spatial\Manhole;
use App\Models\Spatial\PatokHM;
use App\Models\Spatial\PatokKM;
use App\Models\Spatial\PatokLJ;
use App\Models\Spatial\PatokPemandu;
use App\Models\Spatial\PatokRMJ;
use App\Models\Spatial\PatokROW;
use App\Models\Spatial\RambuLalulintas;
use App\Models\Spatial\RambuPenunjukArah;
use App\Models\Spatial\Reflektor;
use App\Models\Spatial\RumahKabel;
use App\Models\Spatial\StaText;
use App\Models\Spatial\TiangListrik;
use App\Models\Spatial\TiangTelepon;
use App\Models\Spatial\VMS;

class MainController extends Controller
{
    public function administratif() 
    {
        $administratif_data = Administratif::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $administratif_features = GeoJSONResource::collection($administratif_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $administratif_features,
        ];

        return response()->json($featureCollection);
    }

    public function geometrik_lingkungan() 
    {
        $geometrik_lingkungan_data = GeometrikLingkungan::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $geometrik_lingkungan_features = GeoJSONResource::collection($geometrik_lingkungan_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $geometrik_lingkungan_features,
        ];

        return response()->json($featureCollection);
    }

    public function iri() 
    {
        $iri_data = IRI::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $iri_features = GeoJSONResource::collection($iri_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $iri_features,
        ];

        return response()->json($featureCollection);
    }

    public function lhr() 
    {
        $lhr_data = LHR::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $lhr_features = GeoJSONResource::collection($lhr_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $lhr_features,
        ];

        return response()->json($featureCollection);
    }

    public function sta_text() 
    {
        $sta_text_data = StaText::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $sta_text_features = GeoJSONResource::collection($sta_text_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $sta_text_features,
        ];

        return response()->json($featureCollection);
    }

    public function lampu_lalulintas() 
    {
        $lampu_lalulintas_data = LampuLalulintas::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $lampu_lalulintas_features = GeoJSONResource::collection($lampu_lalulintas_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $lampu_lalulintas_features,
        ];

        return response()->json($featureCollection);
    }

    public function manhole() 
    {
        $manhole_data = Manhole::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $manhole_features = GeoJSONResource::collection($manhole_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $manhole_features,
        ];

        return response()->json($featureCollection);
    }

    public function jembatan() 
    {
        $jembatan_data = Jembatan::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $jembatan_features = GeoJSONResource::collection($jembatan_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $jembatan_features,
        ];

        return response()->json($featureCollection);
    }

    public function jembatan_polygon() 
    {
        $jembatan_polygon_data = JembatanPolygon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $jembatan_polygon_features = GeoJSONResource::collection($jembatan_polygon_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $jembatan_polygon_features,
        ];

        return response()->json($featureCollection);
    }

    public function patok_hm() 
    {
        $patok_hm_data = PatokHM::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $patok_hm_features = GeoJSONResource::collection($patok_hm_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $patok_hm_features,
        ];

        return response()->json($featureCollection);
    }

    public function patok_km() 
    {
        $patok_km_data = PatokKM::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $patok_km_features = GeoJSONResource::collection($patok_km_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $patok_km_features,
        ];

        return response()->json($featureCollection);
    }

    public function patok_lj() 
    {
        $patok_lj_data = PatokLJ::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $patok_lj_features = GeoJSONResource::collection($patok_lj_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $patok_lj_features,
        ];

        return response()->json($featureCollection);
    }

    public function patok_rmj() 
    {
        $patok_rmj_data = PatokRMJ::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $patok_rmj_features = GeoJSONResource::collection($patok_rmj_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $patok_rmj_features,
        ];

        return response()->json($featureCollection);
    }

    public function patok_row() 
    {
        $patok_row_data = PatokROW::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $patok_row_features = GeoJSONResource::collection($patok_row_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $patok_row_features,
        ];

        return response()->json($featureCollection);
    }

    public function patok_pemandu() 
    {
        $patok_pemandu_data = PatokPemandu::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $patok_pemandu_features = GeoJSONResource::collection($patok_pemandu_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $patok_pemandu_features,
        ];

        return response()->json($featureCollection);
    }

    public function reflektor() 
    {
        $reflektor_data = Reflektor::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $reflektor_features = GeoJSONResource::collection($reflektor_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $reflektor_features,
        ];

        return response()->json($featureCollection);
    }

    public function rambu_lalulintas() 
    {
        $rambu_lalulintas_data = RambuLalulintas::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $rambu_lalulintas_features = GeoJSONResource::collection($rambu_lalulintas_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $rambu_lalulintas_features,
        ];

        return response()->json($featureCollection);
    }

    public function rambu_penunjuk_arah() 
    {
        $rambu_penunjuk_arah_data = RambuPenunjukArah::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $rambu_penunjuk_arah_features = GeoJSONResource::collection($rambu_penunjuk_arah_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $rambu_penunjuk_arah_features,
        ];

        return response()->json($featureCollection);
    }

    public function rumah_kabel() 
    {
        $rumah_kabel_data = RumahKabel::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $rumah_kabel_features = GeoJSONResource::collection($rumah_kabel_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $rumah_kabel_features,
        ];

        return response()->json($featureCollection);
    }

    public function tiang_listrik() 
    {
        $tiang_listrik_data = TiangListrik::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $tiang_listrik_features = GeoJSONResource::collection($tiang_listrik_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $tiang_listrik_features,
        ];

        return response()->json($featureCollection);
    }

    public function tiang_telepon() 
    {
        $tiang_telepon_data = TiangTelepon::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $tiang_telepon_features = GeoJSONResource::collection($tiang_telepon_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $tiang_telepon_features,
        ];

        return response()->json($featureCollection);
    }

    public function vms() 
    {
        $vms_data = VMS::selectRaw("*, ST_AsGeoJSON(ST_Transform(geom, 4326)) AS geojson")->get()->makeHidden('geom');
        $vms_features = GeoJSONResource::collection($vms_data);

        $featureCollection = [
            "type" => "FeatureCollection",
            "features" => $vms_features,
        ];

        return response()->json($featureCollection);
    }
}
