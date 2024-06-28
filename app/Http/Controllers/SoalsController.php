<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Matakuliah;
use App\Models\KomentarSoal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use HtmlToRtf\HtmlToRtf;

class SoalsController extends Controller
{

    public function addSoal(Request $request)
    {
        $isi_soal = $request->file("formFile");
        $isi_soaltext = $request->input("textareaContent");

        $id_mk = $request->input("matakuliah2");
        $nama_soal = $request->input("nama_soal");
        $nama = auth()->user()->nama;
   
        if (!$isi_soal) {
            if (!$isi_soaltext) {
                return redirect("/manageSoal");
            }

            if (!Storage::disk('public')->exists('html')) {
                Storage::disk('public')->makeDirectory('html');
            }
            
            $fileName = time() . '_' . $nama_soal . '_' . $nama . '.html';
            Storage::disk('public')->put('html/' . $fileName, $isi_soaltext);


            Soal::create([
                'nama' => $nama,
                'id_mk' => $id_mk,
                'nama_soal' => $nama_soal,
                'isi_soal' => $fileName
            ]);
            return redirect("/manageSoal");
        }

        $fileName = time() . '_' . $isi_soal->getClientOriginalName();
        $isi_soal->storeAs('pdf', $fileName, 'public');


        Soal::create([
            'nama' => $nama,
            'id_mk' => $id_mk,
            'nama_soal' => $nama_soal,
            'isi_soal' => $fileName
        ]);
        return redirect("/manageSoal");
    }


    public function showsoal(Request $request)
    {
        $id_mk = $request->input("matakuliah_id");
        $mk = Matakuliah::where("id", $id_mk)->orderBy("id", "asc")->firstOrFail();
        $soal = Soal::where("id_mk", $id_mk)->orderBy("id", "asc")->get();
        $id = $request->input("edit");


        session()->put("namamk", $mk->nama);
        session()->put("id_matakuliah", $id_mk);

        return view("soal", compact('soal'));
    }

    public function lihatsoal(Request $request)
    {
        $id_soal = $request->input("soals_id");
        $soal = Soal::where("id", $id_soal)->orderBy("id", "asc")->get();
        $komentar_soal = KomentarSoal::where("id_soal", $id_soal)->orderBy("id", "asc")->get();

        return view("lihatsoal", compact('soal', 'komentar_soal'));
    }

    public function komentar(Request $request)
    {
        $id_soal = $request->input("soals_id");
        $nama = auth()->user()->nama;
        $isi_komentar = $request->input("isi_komentar");
        $hapus = $request->input("hapus");
        $id = $request->input("komentar_id");

        if ($hapus == "1") {
            KomentarSoal::where("id", $id)->delete();
            return redirect("/lihatsoal?soals_id=" . $id_soal);
        }

        if (!$nama || !$isi_komentar) {
            return redirect("/lihatsoal?soals_id=" . $id_soal);
        }

        KomentarSoal::create([
            'id_soal' => $id_soal,
            'nama_komentar' => $nama,
            'isi_komentar' => $isi_komentar,
        ]);

        return redirect("/lihatsoal?soals_id=" . $id_soal);
    }

    public function editmatakuliah($response, $request, $session)
    {
        $semester = $request->input("semester");
        $cancel = $request->input("cancel");

        if ($session->get("edit") == 1) {
            if ($cancel == "2") {
                return $response->redirect("/semester?semester=" . $semester);
            } else {
                $kode_mk = $request->input("kode");
                $nama_mk = $request->input("nama");
                $sks = $request->input("sks");

                if (!$kode_mk) {
                    $session->flash('error', 'Error! KodeMata Kuliah tidak boleh kosong!');
                    return $response->redirect("/semester?semester=" . $semester);
                }
                if (!$nama_mk) {
                    $session->flash('error', 'Error! Nama Mata Kuliah tidak boleh kosong!');
                    return $response->redirect("/semester?semester=" . $semester);
                }
                if (!$sks) {
                    $session->flash('error', 'Error! SKS tidak boleh kosong!');
                    return $response->redirect("/semester?semester=" . $semester);
                }

                Matakuliah::create([
                    'id_semester' => $semester,
                    'kode' => $kode_mk,
                    'nama' => $nama_mk,
                    'sks' => $sks,
                ]);

                return $response->redirect("/semester?semester=" . $semester);
            }
        } elseif ($session->get("edit") == 3) {
            if ($cancel == "2") {
                return $response->redirect("/semester?semester=" . $semester);
            }
            $id = $request->input("hapus");
            $soal = Soal::where("id_mk", $id)->orderBy("id", "asc")->get();
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();
            Soal::where("id_mk", $id)->delete();
            Matakuliah::where("id", $id)->delete();

            return $response->redirect("/semester?semester=" . $semester);
        } else {
            $session->put("matakuliah_id", $request->input("update"));

            if ($cancel == "2") {
                $session->put("matakuliah_id", -1);
                return $response->redirect("/semester?semester=" . $semester);
            } elseif ($cancel == "3") {
                $id = $request->input("update");
                $kode_mk = $request->input("kode");
                $nama_mk = $request->input("nama");
                $sks = $request->input("sks");

                if (!$kode_mk) {
                    $session->flash('error', 'Error! Kode Mata Kuliah tidak boleh kosong!');
                    return $response->redirect("/semester?semester=" . $semester);
                }
                if (!$nama_mk) {
                    $session->flash('error', 'Error! Nama Mata Kuliah tidak boleh kosong!');
                    return $response->redirect("/semester?semester=" . $semester);
                }
                if (!$sks) {
                    $session->flash('error', 'Error! SKS tidak boleh kosong!');
                    return $response->redirect("/semester?semester=" . $semester);
                }

                Matakuliah::where("id", $id)->update([
                    'id_semester' => $semester,
                    'kode' => $kode_mk,
                    'nama' => $nama_mk,
                    'sks' => $sks,
                ]);

                $session->put("matakuliah_id", -1);
                return $response->redirect("/semester?semester=" . $semester);
            }

            return $response->redirect("/semester?semester=" . $semester . "&edit=2");
        }
    }

    public function editsoal(Request $request)
    {
        $id_matakuliah = $request->input("matakuliah_id");
        $cancel = $request->input("cancel");

        if (session()->get("edit") == 3) {
            if ($cancel == "2") {
                return redirect("/soal?matakuliah_id=" . $id_matakuliah);
            }
            $id = $request->input("hapus");

            KomentarSoal::where("id_soal", $id)->delete();
            Soal::where("id", $id)->delete();

            return redirect("/soal?matakuliah_id=" . $id_matakuliah);
        }
    }
}
