<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Matakuliah;
use App\Models\KomentarSoal;
use App\Models\rating_komentar;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use HtmlToRtf\HtmlToRtf;
use Illuminate\Support\Facades\Log;

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
            $tipe = 2;
            $fileName = time() . '_' . $nama_soal . '_' . $nama . '.html';
            Storage::disk('public')->put('html/' . $fileName, $isi_soaltext);


            Soal::create([
                'nama' => $nama,
                'id_mk' => $id_mk,
                'nama_soal' => $nama_soal,
                'tipe' => $tipe,
                'isi_soal' => $fileName
            ]);
            return redirect("/manageSoal");
        }

        $fileName = time() . '_' . $isi_soal->getClientOriginalName();
        $isi_soal->storeAs('pdf', $fileName, 'public');

        $tipe = 1;
        Soal::create([
            'nama' => $nama,
            'id_mk' => $id_mk,
            'nama_soal' => $nama_soal,
            'tipe' => $tipe,
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
        $user_ids = $komentar_soal->pluck('id_user')->unique();
        $user = User::whereIn("id", $user_ids)->orderBy("id", "asc")->get()->keyBy('id');

        $usernow = Auth::user();

        $ratings = rating_komentar::where('id_user', $usernow->id)
            ->whereIn('id_komentar', $komentar_soal->pluck('id'))
            ->get()
            ->keyBy('id_komentar');

        $ratingData = rating_komentar::selectRaw('id_komentar, AVG(rating) as avg_rating, COUNT(id) as rating_count')
            ->whereIn('id_komentar', $komentar_soal->pluck('id'))
            ->groupBy('id_komentar')
            ->get()
            ->keyBy('id_komentar');

        $komentar_parents = $komentar_soal->whereNull('parent_id');
        $komentar_replies = $komentar_soal->whereNotNull('parent_id')->groupBy('parent_id');

        return view("lihatsoal", compact('soal', 'komentar_parents', 'komentar_replies', 'user', 'usernow', 'ratings', 'ratingData'));
    }

    public function komentar(Request $request)
    {
        $request->validate([
            'id_soal' => 'required|exists:soal,id',
            'isi_komentar' => 'required|string|max:255',
            'file_komentar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:komentar_soal,id',
        ]);
    
        $komentar = new KomentarSoal();
        $komentar->id_soal = $request->id_soal;
        $komentar->id_user = Auth::id();
        $komentar->isi_komentar = $request->isi_komentar;
        if ($request->filled('parent_id')) {
            $komentar->parent_id = $request->parent_id;
        }
        $komentar->save();
    
        $images = [];
        if ($request->hasfile('file_komentar')) {
            $destinationPath = 'public/komentarSoal/' . $komentar->id_soal;
            $i = 1;
            foreach ($request->file('file_komentar') as $file) {
                $filename = $komentar->id . '_' . $komentar->id_user . '_' . $i . '.' . $file->getClientOriginalExtension();
                $file->storeAs($destinationPath, $filename);
                $images[] = $filename;
                $i++;
            }
            $komentar->file_komentar = json_encode($images);
        }
    
        $komentar->save();
    
        return response()->json(['success' => true]);
    }
    


    public function submitRating(Request $request)
    {
        $request->validate([
            'id_komentar' => 'required|exists:komentar_soal,id',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $rating = rating_komentar::updateOrCreate(
            [
                'id_komentar' => $request->id_komentar,
                'id_user' => Auth::id()
            ],
            [
                'rating' => $request->rating
            ]
        );

        $komentar = KomentarSoal::find($request->id_komentar);
        $avgRating = rating_komentar::where('id_komentar', $request->id_komentar)
            ->avg('rating');
        $ratingCount = rating_komentar::where('id_komentar', $request->id_komentar)
            ->count();

        return response()->json([
            'success' => true,
            'avg_rating' => number_format($avgRating, 1),
            'rating_count' => $ratingCount,
            'komentar_id' => $request->id_komentar
        ]);
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
