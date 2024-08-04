<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Jenjang;
use App\Models\Prodi;
use App\Models\Semester;
use App\Models\multi_mk;
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
        $matakuliah = Matakuliah::all();


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
        $matakuliah = Matakuliah::all();
        
        return view("semester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
    }
    public function cariSemester(Request $request)
    {
        $id_semester = $request->input("semester");
        $s = Semester::where("id", $id_semester)->firstOrFail();
    
        session()->put("semester", $id_semester);
        session()->put("semester_nama", $s->nama);
        session()->put("jenjang", $request->input("jenjang"));
        session()->put("fakultas", $request->input("fakultas"));
        session()->put("prodi", $request->input("prodi"));
        session()->put("semester", $id_semester);
    
        $multi_mk = multi_mk::where("id_semester", $id_semester)->orderBy("id_mk", "asc")->get();
        $matakuliah = Matakuliah::whereIn("id", $multi_mk->pluck('id_mk')->toArray())->orderBy("id", "asc")->get();
        $sks = $matakuliah->sum('sks');
    
        // สร้าง view fragment
        $html = view('partials.matakuliah', compact('matakuliah', 'sks'))->render();
    
        return response()->json(['html' => $html]);
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
        $matakuliah = Matakuliah::all();
        
        return view("semester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
    }

    public function index()
    {
        session()->forget("jenjang");
        session()->forget("fakultas");
        session()->forget("prodi");
        session()->forget("semester");
        session()->forget("matakuliah");
        session()->forget("soal");
        session()->forget("jenjang_nama");
        session()->forget("fakultas_nama");
        session()->forget("prodi_nama");
        session()->forget("semester_nama");
        session()->forget("matakuliah_nama");
        
        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah = Matakuliah::all();

        return view("semester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
    }
}
