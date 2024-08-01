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
    
        if ($isi_soal) {
            // Check if the file size exceeds 5MB
            $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
            if ($isi_soal->getSize() > $maxFileSize) {
                return redirect()->back()->with('error', 'File size exceeds 5MB.');
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
        } else {
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
            
        if($id_soal == null){
            $id_soal = session()->get("id_soal");
        }
        session()->put("id_soal", $id_soal);
    
        // รับพารามิเตอร์การเรียงลำดับจาก request
        $sort = $request->input('sort', 'newest'); // ค่าเริ่มต้นคือ 'newest'
    
        // จัดการการเรียงลำดับตามพารามิเตอร์
        if ($sort == 'newest') {
            $komentar_soal = KomentarSoal::where("id_soal", $id_soal)->orderBy("created_at", "desc")->get();
        } elseif ($sort == 'oldest') {
            $komentar_soal = KomentarSoal::where("id_soal", $id_soal)->orderBy("created_at", "asc")->get();
        } elseif ($sort == 'most_rated') {
            $komentar_soal = KomentarSoal::where("id_soal", $id_soal)
                ->leftJoin('rating_komentar', 'komentar_soal.id', '=', 'rating_komentar.id_komentar')
                ->selectRaw('komentar_soal.*, COUNT(rating_komentar.id) as rating_count')
                ->groupBy('komentar_soal.id', 'komentar_soal.id_soal', 'komentar_soal.id_user', 'komentar_soal.parent_id', 'komentar_soal.isi_komentar', 'komentar_soal.file_komentar', 'komentar_soal.created_at', 'komentar_soal.updated_at')
                ->orderBy('rating_count', 'desc')
                ->get();
        }
    
        $soal = Soal::where("id", $id_soal)->orderBy("id", "asc")->get();
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
    
        if ($request->ajax()) {
            return view('partials.comments', compact('komentar_parents', 'komentar_replies', 'user', 'ratings', 'ratingData', 'sort'))->render();
        }
    
        return view("lihatsoal", compact('soal', 'komentar_parents', 'komentar_replies', 'user', 'usernow', 'ratings', 'ratingData', 'sort'));
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

    public function deleteComment($id)
    {
        $komentar = KomentarSoal::find($id);

        if ($komentar) {
            // ลบเรตติ้งของคอมเม้น
            rating_komentar::where('id_komentar', $id)->delete();

            // ค้นหาคอมเม้นลูกทั้งหมด
            $childComments = KomentarSoal::where('parent_id', $id)->get();

            // ลบคอมเม้นลูกทั้งหมด
            foreach ($childComments as $childComment) {
                rating_komentar::where('id_komentar', $childComment->id)->delete();
                $this->deleteCommentFiles($childComment);
                $childComment->delete();
            }

            // ลบไฟล์ภาพของคอมเม้นหลัก
            $this->deleteCommentFiles($komentar);

            // ลบคอมเม้นหลัก
            $komentar->delete();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Comment not found']);
        }
    }

    private function deleteCommentFiles($komentar)
    {
        if ($komentar->file_komentar) {
            $images = json_decode($komentar->file_komentar);
            foreach ($images as $image) {
                $path = 'public/komentarSoal/' . $komentar->id_soal . '/' . $image;
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
        }
    }
  
    
    
}
