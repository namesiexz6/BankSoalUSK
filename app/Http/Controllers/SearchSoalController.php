<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Jenjang;
use App\Models\Prodi;
use App\Models\Semester;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SearchSoalController extends Controller
{
    
    public function cariFakultas(Request $request)
    {

        $id_fakultas = $request->input("fakultas");
        session()->put("id_fakultas", $id_fakultas);
        $f = Fakultas::where("id", $id_fakultas)->orderBy("id", "asc")->firstOrFail();
        session()->put("fakultas", $f->nama);

        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah=Matakuliah::all();

        session()->forget("id_prodi");
        session()->forget("id_semester");
        session()->forget("prodi");
        session()->forget("semester");

        return view("semester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
    }
    public function cariProdi(Request $request)
    {

        $id_prodi = $request->input("prodi");
        $p = Prodi::where("id", $id_prodi)->orderBy("id", "asc")->firstOrFail();
        session()->put("id_prodi", $id_prodi);
        session()->put("prodi", $p->nama);

        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah=Matakuliah::all();

        session()->forget("id_semester");
        session()->forget("semester");

        return view("semester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
    }
    public function cariSemester(Request $request)
    {
        $id_semester = $request->input("semester");
        $s = Semester::where("id", $id_semester)->orderBy("id", "asc")->firstOrFail();
        session()->put("id_semester", $id_semester);
        session()->put("semester", $s->nama);

        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah = Matakuliah::where("id_semester", $id_semester)->orderBy("id", "asc")->get();
        $sks = 0;

        foreach ($matakuliah as $mkItem) {
            $sks += $mkItem->sks;
        }
;

        return view("semester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah', 'sks'));
    }
   

    public function cariJenjang(Request $request)
    {
        $id_jenjang = $request->input("jenjang");
        $jj = Jenjang::where("id", $id_jenjang)->orderBy("id", "asc")->firstOrFail();

        $request->session()->put("id_jenjang", $id_jenjang);
        $request->session()->put("jenjang", $jj->nama);
  
        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah=Matakuliah::all();
        session()->forget("id_fakultas");
        session()->forget("id_prodi");
        session()->forget("id_semester");
        session()->forget("fakultas");
        session()->forget("prodi");
        session()->forget("semester");
        session()->forget("id_matakuliah");
        session()->forget("matakuliah");

        return view("semester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
    }

    public function index()
    {
        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah=Matakuliah::all();

        return view("semester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
    }
}
