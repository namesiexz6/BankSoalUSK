<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Jenjang;
use App\Models\Prodi;
use App\Models\Semester;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use App\Models\KomentarSoal;
use App\Models\Soal;


class ManagementController extends Controller
{

    public function addJenjang(Request $request)
    {
        $nama = $request->input("nama_jenjang");
        Jenjang::create([
            'nama' => $nama

        ]);

        return redirect("/manageJenjang");
    }
    public function addFakultas(Request $request)
    {
        $nama = $request->input("nama_fakultas");
        $id_jenjang = $request->input("jenjang2");
        Fakultas::create([
            'id_jenjang' => $id_jenjang,
            'nama' => $nama

        ]);
        return redirect("/manageFakultas");
    }
    public function addProdi(Request $request)
    {
        $nama_ = $request->input("nama_prodi");
        $id_fakultas = $request->input("fakultas2");
        //dd(request()->all());
        Prodi::create([
            'id_fakultas' => $id_fakultas,
            'nama' => $nama_,

        ]);
        return redirect("/manageProdi");
    }
    public function addSemester(Request $request)
    {
        $nama_ = $request->input("nama_semester");
        $id_prodi = $request->input("prodi2");
        //dd(request()->all());
        Semester::create([
            'id_prodi' => $id_prodi,
            'nama' => $nama_,

        ]);
        return redirect("/manageSemester");
    }
    public function addMatakuliah(Request $request)
    {
        $id_semester = $request->input("id_semester");
        $nama = $request->input("nama");
        $sks = $request->input("sks");

        Matakuliah::create([
            'id_semester' => $id_semester,
            'nama' => $nama,
            'sks' => $sks,

        ]);

        return redirect("/");
    }
    public function jenjangM(Request $request)
    {
        $jenjang = $request->input("edit");
        if ($jenjang == 2) {
            $id = $request->input("jenjang_id");
            $fakultas = Fakultas::where("id_jenjang", $id)->orderBy("id", "asc")->get();
            $prodi = Prodi::whereIn("id_fakultas", $fakultas->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $semester = Semester::whereIn("id_prodi", $prodi->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $mk = Matakuliah::whereIn("id_semester", $semester->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $soal = Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();
            Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();
            Matakuliah::whereIn("id_semester", $semester->pluck('id')->toArray())->delete();
            Semester::whereIn("id_prodi", $prodi->pluck('id')->toArray())->delete();
            Prodi::whereIn("id_fakultas", $fakultas->pluck('id')->toArray())->delete();
            Fakultas::where("id_jenjang", $id)->delete();
            Jenjang::where("id", $id)->delete();
            return redirect("/manageJenjang");
        } elseif ($jenjang == 1) {
            $id = $request->input("jenjang_id");
            $nama = $request->input("nama_jenjang");
            //dd($nama);
            Jenjang::where('id', $id)->update([
                'nama' => $nama,

            ]);
            return redirect('/manageJenjang');
        } else {

            return redirect("/management");
        }
    }

    public function fakultasM(Request $request)
    {
        $fakultas = $request->input("edit");
        if ($fakultas == 2) {
            $id = $request->input("fakultas_id");
            $prodi = Prodi::where("id_fakultas", $id)->orderBy("id", "asc")->get();
            $semester = Semester::whereIn("id_prodi", $prodi->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $mk = Matakuliah::whereIn("id_semester", $semester->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $soal = Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();
            Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();
            Matakuliah::whereIn("id_semester", $semester->pluck('id')->toArray())->delete();
            Semester::whereIn("id_prodi", $prodi->pluck('id')->toArray())->delete();
            Prodi::where("id_fakultas", $id)->delete();
            Fakultas::where("id", $id)->delete();
            return redirect("/manageFakultas");
        } elseif ($fakultas == 1) {
            $id = $request->input("fakultas_id");
            $id_jenjang = $request->input("jenjang2");
            $nama = $request->input("nama_fakultas");
            //dd($nama);
            Fakultas::where('id', $id)->update([
                'id_jenjang' => $id_jenjang,
                'nama' => $nama,
            ]);
            return redirect('/manageFakultas');
        } else {

            return redirect("/management");
        }
    }

    public function prodiM(Request $request)
    {

        $prodi = $request->input("edit");
        if ($prodi == 2) {
            $id = $request->input("prodi_id");
            $semester = Semester::where("id_prodi", $id)->orderBy("id", "asc")->get();
            $mk = Matakuliah::whereIn("id_semester", $semester->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $soal = Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();
            Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();
            Matakuliah::whereIn("id_semester", $semester->pluck('id')->toArray())->delete();
            Semester::where("id_prodi", $id)->delete();
            Prodi::where("id", $id)->delete();

            return redirect("/manageProdi");
        } elseif ($prodi == 1) {
            $id = $request->input("prodi_id");
            $id_fakultas = $request->input("fakultas2");
            $nama = $request->input("nama_prodi");
            //dd($nama);
            Prodi::where('id', $id)->update([
                'id_fakultas' => $id_fakultas,
                'nama' => $nama,
            ]);
            return redirect('/manageProdi');
        } else {

            return redirect("/management");
        }
    }

    public function semesterM(Request $request)
    {

        $semester = $request->input("edit");

        if ($semester == 2) {

            $id = $request->input("semester_id");
            $mk = Matakuliah::where("id_semester", $id)->orderBy("id", "asc")->get();
            $soal = Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();
            Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();
            Matakuliah::where("id_semester", $id)->delete();
            Semester::where("id", $id)->delete();


            return redirect("/manageSemester");
        } elseif ($semester == 1) {
            $id = $request->input("semester_id");
            $id_prodi = $request->input("prodi2");
            $nama = $request->input("nama_semester");
            //dd($nama);
            Semester::where('id', $id)->update([
                'id_prodi' => $id_prodi,
                'nama' => $nama,
            ]);
            return redirect('/manageSemester');
        } else {

            return redirect("/management");
        }
    }

    public function matakuliahM(Request $request)
    {
        $matakuliah = $request->input("edit");

        if ($matakuliah == 2) {

            $id = $request->input("matakuliah_id");
            $soal = Soal::where("id_mk", $id)->orderBy("id", "asc")->get();
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();
            Soal::where("id_mk", $id)->delete();
            Matakuliah::where("id", $id)->delete();

            return redirect("/manageMatakuliah");
        } else {

            return redirect("/management");
        }
    }

    public function soalM(Request $request)
    {

        $soal = $request->input("edit");

        if ($soal == 2) {

            $id = $request->input("soal_id");
            KomentarSoal::where("id_soal", $id)->delete();
            Soal::where("id", $id)->delete();

            return redirect("/manageSoal");
        } elseif ($soal == 1) {
            $id = $request->input("soal_id");


            return redirect('/manageSoal');
        } else {

            return redirect("/management");
        }
    }




    public function cariJenjangM(Request $request)
    {

        $id_jenjang = $request->input("jenjang");
        $jj = Jenjang::where("id", $id_jenjang)->orderBy("id", "asc")->firstOrFail();
        session()->put("jenjang", $id_jenjang);
        session()->put("jenjang_nama", $jj->nama);

        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah = Matakuliah::all();

        if (session("Manage_id") == 1) {
            return view("management/manageSoal", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 2) {
            return view("management/manageMatakuliah", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 3) {
            return view("management/manageSemester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 4) {
            return view("management/manageProdi", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 5) {
            $fakultas = Fakultas::where("id_jenjang", $id_jenjang)->orderBy("id", "asc")->get();
            return view("management/manageFakultas", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 6) {
            return view("management/manageJenjang", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } else {
            return view("management/management", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        }
    }

    public function cariFakultasM(Request $request)
    {

        $id_fakultas = $request->input("fakultas");
        session()->put("id_fakultas", $id_fakultas);
        $f = Fakultas::where("id", $id_fakultas)->orderBy("id", "asc")->firstOrFail();
        session()->put("fakultas", $id_fakultas);
        session()->put("fakultas_nama", $f->nama);
        session()->put("jenjang", $request->input("jenjang"));

        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah = Matakuliah::all();



        if (session("Manage_id") == 1) {
            return view("management/manageSoal", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 2) {
            return view("management/manageMatakuliah", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 3) {
            return view("management/manageSemester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 4) {
            $prodi = Prodi::where("id_fakultas", $id_fakultas)->orderBy("id", "asc")->get();
            return view("management/manageProdi", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 5) {
            return view("management/manageFakultas", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 6) {
            return view("management/manageJenjang", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } else {
            return view("management/management", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        }
    }


    public function cariProdiM(Request $request)
    {

        $id_prodi = $request->input("prodi");
        $p = Prodi::where("id", $id_prodi)->orderBy("id", "asc")->firstOrFail();
        session()->put("prodi", $id_prodi);
        session()->put("prodi_nama", $p->nama);
        session()->put("jenjang", $request->input("jenjang"));
        session()->put("fakultas", $request->input("fakultas"));

        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah = Matakuliah::all();


        if (session("Manage_id") == 1) {
            return view("management/manageSoal", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 2) {
            return view("management/manageMatakuliah", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 3) {
            $semester = Semester::where("id_prodi", $id_prodi)->orderBy("id", "asc")->get();
            return view("management/manageSemester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 4) {
            return view("management/manageProdi", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 5) {
            return view("management/manageFakultas", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 6) {
            return view("management/manageJenjang", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } else {
            return view("management/management", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        }
    }
    public function cariSemesterM(Request $request)
    {
        $id_semester = $request->input("semester");
        $s = Semester::where("id", $id_semester)->firstOrFail();
        session()->put("semester", $id_semester);
        session()->put("semester_nama", $s->nama);
        session()->put("jenjang", $request->input("jenjang"));
        session()->put("fakultas", $request->input("fakultas"));
        session()->put("prodi", $request->input("prodi"));



        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah = Matakuliah::all();

        if (session("Manage_id") == 1) {
            return view("management/manageSoal", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 2) {
            $matakuliah = Matakuliah::where("id_semester", $id_semester)->orderBy("id", "asc")->get();


            return view("management/manageMatakuliah", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 3) {
            return view("management/manageSemester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 4) {
            return view("management/manageProdi", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 5) {
            return view("management/manageFakultas", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 6) {
            return view("management/manageJenjang", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } else {
            return view("management/management", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        }
    }

    public function cariMatakuliahM(Request $request)
    {

        $id_matakuliah = $request->input("matakuliah");
        $m = Matakuliah::where("id", $id_matakuliah)->orderBy("id", "asc")->firstOrFail();
        session()->put("matakuliah", $id_matakuliah);
        session()->put("matakuliah_nama", $m->nama);
        session()->put("jenjang", $request->input("jenjang"));
        session()->put("fakultas", $request->input("fakultas"));
        session()->put("prodi", $request->input("prodi"));
        session()->put("semester", $request->input("semester"));

        $jenjang = Jenjang::all();
        $fakultas = Fakultas::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $matakuliah = Matakuliah::all();
        $soal = Soal::all();

        if (session("Manage_id") == 1) {
            $soal = Soal::where("id_mk", $id_matakuliah)->orderBy("id", "asc")->get();
            return view("management/manageSoal", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah', 'soal'));
        } elseif (session("Manage_id") == 2) {
            return view("management/manageMatakuliah", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 3) {
            return view("management/manageSemester", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 4) {
            return view("management/manageProdi", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 5) {
            return view("management/manageFakultas", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } elseif (session("Manage_id") == 6) {
            return view("management/manageJenjang", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        } else {
            return view("management/management", compact('jenjang', 'fakultas', 'prodi', 'semester', 'matakuliah'));
        }
    }

    public function cariFakultasM2(Request $request)
    {

        $data['fakultas'] = Fakultas::where("id_jenjang", $request->id_jenjang)
            ->get(["nama", "id"]);

        return response()->json($data);
    }

    public function cariProdiM2(Request $request)
    {

        $data['prodi'] = Prodi::where("id_fakultas", $request->id_fakultas)
            ->get(["nama", "id"]);

        return response()->json($data);
    }

    public function cariSemesterM2(Request $request)
    {

        $data['semester'] = Semester::where("id_prodi", $request->id_prodi)
            ->get(["nama", "id"]);

        return response()->json($data);
    }

    public function cariMatakuliahM2(Request $request)
    {

        $data['matakuliah'] = Matakuliah::where("id_semester", $request->id_semester)
            ->get(["nama", "id"]);

        return response()->json($data);
    }

    public function indexM()
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

        $data['jenjang'] = Jenjang::get(["nama", "id"]);
        $data['fakultas'] = Fakultas::get(["nama", "id"]);
        $data['prodi'] = Prodi::get(["nama", "id"]);
        $data['semester'] = Semester::get(["nama", "id"]);
        $data['matakuliah'] = Matakuliah::get(["nama", "id"]);
        $data['soal'] = Soal::get(["nama", "id"]);


        session()->put("Manage_id", 0);
        return view("management/management", $data);
    }
    public function indexMs()
    {


        $data['jenjang'] = Jenjang::get(["nama", "id"]);
        $data['fakultas'] = Fakultas::get(["nama", "id"]);
        $data['prodi'] = Prodi::get(["nama", "id"]);
        $data['semester'] = Semester::get(["nama", "id"]);
        $data['matakuliah'] = Matakuliah::get(["nama", "id"]);
        $data['soal'] = Soal::all();
        session()->put("Manage_id", 1);


        return view("management/manageSoal", $data);
    }
    public function indexMs2()
    {


        $data['jenjang'] = Jenjang::get(["nama", "id"]);
        $data['fakultas'] = Fakultas::get(["nama", "id"]);
        $data['prodi'] = Prodi::get(["nama", "id"]);
        $data['semester'] = Semester::get(["nama", "id"]);
        $data['matakuliah'] = Matakuliah::get(["nama", "id"]);
        $data['soal'] = Soal::all();
        session()->put("Manage_id", 1);


        return view("management/uploadSoal", $data);
    }
    public function indexMm()
    {

        $data['jenjang'] = Jenjang::get(["nama", "id"]);
        $data['fakultas'] = Fakultas::get(["nama", "id"]);
        $data['prodi'] = Prodi::get(["nama", "id"]);
        $data['semester'] = Semester::get(["nama", "id"]);
        $data['matakuliah'] = Matakuliah::all();


        session()->put("Manage_id", 2);
        return view("management/manageMatakuliah", $data);
    }
    public function indexMsm()
    {


        $data['jenjang'] = Jenjang::get(["nama", "id"]);
        $data['fakultas'] = Fakultas::get(["nama", "id"]);
        $data['prodi'] = Prodi::get(["nama", "id"]);
        $data['semester'] = Semester::all();
        session()->put("Manage_id", 3);
        return view("management/manageSemester", $data);
    }
    public function indexMp()
    {


        $data['jenjang'] = Jenjang::get(["nama", "id"]);
        $data['fakultas'] = Fakultas::get(["nama", "id"]);
        $data['prodi'] = Prodi::all();

        session()->put("Manage_id", 4);
        return view("management/manageProdi", $data);
    }
    public function indexMf()
    {

        $data['jenjang'] = Jenjang::get(["nama", "id"]);
        $data['fakultas'] = Fakultas::all();

        session()->put("Manage_id", 5);
        return view("management/manageFakultas", $data);
    }
    public function indexMj()
    {

        $data['jenjang'] = Jenjang::all();

        session()->put("Manage_id", 6);
        return view("management/manageJenjang", $data);
    }
}
