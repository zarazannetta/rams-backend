<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\JalanTol;

class RefferenceController extends Controller
{
    public function getRuasList()
    {
        if (Auth::user()->id == 1) {
            $data = JalanTol::get();
        } else {
            $data = JalanTol::where('user_id', Auth::user()->id)->get();
        }
        return response()->json($data);
    }

    public function getTipeAsetList()
    {
        $tipe_aset = [
            ['type' => 'bpt_line', 'text' => 'Line - Bangunan Penahan Tanah'],
            ['type' => 'batas_desa_line', 'text' => 'Line - Batas Desa'],
            ['type' => 'box_culvert_line', 'text' => 'Line - Box Culvert'],
            ['type' => 'bronjong_line', 'text' => 'Line - Bronjong'],
            ['type' => 'concrete_barrier_line', 'text' => 'Line - Concrete Barrier'],
            ['type' => 'gerbang_line', 'text' => 'Line - Gerbang'],
            ['type' => 'gorong_gorong_line', 'text' => 'Line - Gorong-Gorong'],
            ['type' => 'guardrail_line', 'text' => 'Line - Guardrail'],
            ['type' => 'jalan_line', 'text' => 'Line - Jalan'],
            ['type' => 'listrik_bawahtanah_line', 'text' => 'Line - Listrik Bawah Tanah'],
            ['type' => 'marka_line', 'text' => 'Line - Marka'],
            ['type' => 'pagar_operasional_line', 'text' => 'Line - Pagar Operasional'],
            ['type' => 'pita_kejut_line', 'text' => 'Line - Pita Kejut'],
            ['type' => 'riol_line', 'text' => 'Line - Riol'],
            ['type' => 'saluran_line', 'text' => 'Line - Saluran'],
            ['type' => 'sungai_line', 'text' => 'Line - Sungai'],
            ['type' => 'telepon_bawahtanah_line', 'text' => 'Line - Telepon Bawah Tanah'],
            ['type' => 'gerbang_point', 'text' => 'Point - Gerbang'],
            ['type' => 'jembatan_point', 'text' => 'Point - Jembatan'],
            ['type' => 'lampu_lalulintas_point', 'text' => 'Point - Lampu Lalu Lintas'],
            ['type' => 'manhole_point', 'text' => 'Point - Manhole'],
            ['type' => 'patok_hm_point', 'text' => 'Point - Patok Hektometer'],
            ['type' => 'patok_km_point', 'text' => 'Point - Patok Kilometer'],
            ['type' => 'patok_lj_point', 'text' => 'Point - Patok Leger Jalan'],
            ['type' => 'patok_pemandu_point', 'text' => 'Point - Patok Pemandu'],
            ['type' => 'patok_row_point', 'text' => 'Point - Patok Right of Way (ROW)'],
            ['type' => 'patok_rmj_point', 'text' => 'Point - Patok Ruang Milik Jalan (Rumija)'],
            ['type' => 'rambu_lalulintas_point', 'text' => 'Point - Rambu Lalu Lintas'],
            ['type' => 'rambu_penunjukarah_point', 'text' => 'Point - Rambu Penunjuk Arah'],
            ['type' => 'reflektor_point', 'text' => 'Point - Reflektor'],
            ['type' => 'rumah_kabel_point', 'text' => 'Point - Rumah Kabel'],
            ['type' => 'sta_text_point', 'text' => 'Point - STA Text'],
            ['type' => 'tiang_listrik_point', 'text' => 'Point - Tiang Listrik'],
            ['type' => 'tiang_telepon_point', 'text' => 'Point - Tiang Telepon'],
            ['type' => 'vms_point', 'text' => 'Point - Variable Message Sign (VMS)'],
            ['type' => 'administratif_polygon', 'text' => 'Polygon - Administratif'],
            ['type' => 'data_geometrik_jalan_polygon', 'text' => 'Polygon - Data Geometrik Jalan'],
            ['type' => 'iri_polygon', 'text' => 'Polygon - International Roughness Index (IRI)'],
            ['type' => 'jembatan_polygon', 'text' => 'Polygon - Jembatan'],
            ['type' => 'lapis_permukaan_polygon', 'text' => 'Polygon - Lapis Permukaan'],
            ['type' => 'lapis_pondasi_atas1_polygon', 'text' => 'Polygon - Lapis Pondasi Atas 1'],
            ['type' => 'lapis_pondasi_atas2_polygon', 'text' => 'Polygon - Lapis Pondasi Atas 2'],
            ['type' => 'lapis_pondasi_bawah_polygon', 'text' => 'Polygon - Lapis Pondasi Bawah'],
            ['type' => 'lhr_polygon', 'text' => 'Polygon - Lintas Harian Rata-Rata (LHR)'],
            ['type' => 'ruwasja_polygon', 'text' => 'Polygon - Ruang Pengawasan Jalan'],
            ['type' => 'segmen_konstruksi_polygon', 'text' => 'Polygon - Segmen Konstruksi'],
            ['type' => 'segmen_leger_polygon', 'text' => 'Polygon - Segmen Leger'],
            ['type' => 'segmen_perlengkapan_polygon', 'text' => 'Polygon - Segmen Perlengkapan'],
            ['type' => 'segmen_seksi_polygon', 'text' => 'Polygon - Segmen Seksi'],
            ['type' => 'segmen_tol_polygon', 'text' => 'Polygon - Segmen Tol'],
        ];

        return response()->json($tipe_aset);
    }
}
