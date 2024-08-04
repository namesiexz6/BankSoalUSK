<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Jenjang;
use App\Models\KomentarPost;
use App\Models\Prodi;
use App\Models\rating_komentar;
use Illuminate\Support\Facades\Storage;
use App\Models\Semester;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use App\Models\KomentarSoal;
use App\Models\LovePost;
use App\Models\multi_mk;
use App\Models\Post;
use App\Models\rating_komentar_post;
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
        // รับค่าจากฟอร์ม
        $kode = $request->input("kode");
        $nama = $request->input("nama");
        $sks = $request->input("sks");

        // สร้างบันทึกข้อมูลในตาราง matakuliah
        $matakuliah = Matakuliah::create([
            'kode' => $kode,
            'nama' => $nama,
            'sks' => $sks,
        ]);

        $semesters = $request->input("semester2");

        // Loop เพื่อสร้างบันทึกข้อมูลในตาราง multi_mk
        foreach ($semesters as $key => $semester) {
            multi_mk::create([
                'id_mk' => $matakuliah->id,
                'id_semester' => $semester,

            ]);
        }

        return redirect("/manageMatakuliah");
    }
    public function jenjangM(Request $request)
    {
        $jenjang = $request->input("edit");
        if ($jenjang == 2) {
            $id = $request->input("jenjang_id");
            $fakultas = Fakultas::where("id_jenjang", $id)->orderBy("id", "asc")->get();
            $prodi = Prodi::whereIn("id_fakultas", $fakultas->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $semester = Semester::whereIn("id_prodi", $prodi->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $multi_mk = multi_mk::whereIn("id_semester", $semester->pluck('id')->toArray())->orderBy("id_mk", "asc")->get();
            $mk = Matakuliah::whereIn("id", $multi_mk->pluck('id_mk')->toArray())->orderBy("id", "asc")->get();
            $soal = Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $post = Post::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $komentar = KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->get();
            $komentar_post = KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->get();

            foreach ($post as $pst) {
                if ($pst->file_post) {
                    $images = json_decode($pst->file_post);
                    foreach ($images as $image) {
                        Storage::delete('public/post/' . $pst->id . '/' . $image);
                    }
                }
            }
            foreach ($soal as $soals) {
                if ($soals->tipe == 1) {
                    Storage::delete('public/pdf/' . $soals->isi_soal);
                } elseif ($soals->tipe == 2) {
                    Storage::delete('public/html/' . $soals->isi_soal);
                }
            }
            foreach ($komentar as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    foreach ($images as $image) {
                        Storage::delete('public/komentarSoal/' . $kmt->id_soal . '/' . $image);
                    }
                }
            }
            foreach ($komentar_post as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    foreach ($images as $image) {
                        Storage::delete('public/komentarPost/' . $kmt->id_post . '/' . $image);
                    }
                }
            }
            LovePost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            $komentarPost_id = $komentar_post->pluck('id');
            rating_komentar_post::whereIn("id_komentar", $komentarPost_id)->delete();
            KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            Post::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();
            $komentar_ids = $komentar->pluck('id');
            rating_komentar::whereIn('id_komentar', $komentar_ids)->delete();
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();
            Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();
            multi_mk::whereIn("id_semester", $semester->pluck('id')->toArray())->delete();
            Matakuliah::whereIn("id", $multi_mk->pluck('id_mk')->toArray())->delete();
            Semester::whereIn("id_prodi", $prodi->pluck('id')->toArray())->delete();
            Prodi::whereIn("id_fakultas", $fakultas->pluck('id')->toArray())->delete();
            Fakultas::where("id_jenjang", $id)->delete();
            Jenjang::where("id", $id)->delete();

            return redirect("/manageJenjang");
        } elseif ($jenjang == 1) {
            $id = $request->input("jenjang_id");
            $nama = $request->input("nama_jenjang");
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
            $multi_mk = multi_mk::whereIn("id_semester", $semester->pluck('id')->toArray())->orderBy("id_mk", "asc")->get();
            $mk = Matakuliah::whereIn("id", $multi_mk->pluck('id_mk')->toArray())->orderBy("id", "asc")->get();
            $soal = Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $post = Post::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $komentar = KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->get();
            $komentar_post = KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->get();

            foreach ($komentar as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    foreach ($images as $image) {
                        Storage::delete('public/komentarSoal/' . $kmt->id_soal . '/' . $image);
                    }
                }
            }
            foreach ($post as $pst) {
                if ($pst->file_post) {
                    $images = json_decode($pst->file_post);
                    foreach ($images as $image) {
                        Storage::delete('public/post/' . $pst->id . '/' . $image);
                    }
                }
            }
            foreach ($soal as $soals) {
                if ($soals->tipe == 1) {
                    Storage::delete('public/pdf/' . $soals->isi_soal);
                } elseif ($soals->tipe == 2) {
                    Storage::delete('public/html/' . $soals->isi_soal);
                }
            }
            foreach ($komentar_post as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    foreach ($images as $image) {
                        Storage::delete('public/komentarPost/' . $kmt->id_post . '/' . $image);
                    }
                }
            }

            LovePost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            $komentarPost_id = $komentar_post->pluck('id');
            rating_komentar_post::whereIn("id_komentar", $komentarPost_id)->delete();
            KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            Post::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();
            $komentar_ids = $komentar->pluck('id');
            rating_komentar::whereIn('id_komentar', $komentar_ids)->delete();

            // ลบคอมเม้นที่เกี่ยวข้อง
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();

            // ลบคำถาม
            Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();

            // ลบ multi_mk, Matakuliah, Semester, Prodi และ Fakultas
            multi_mk::whereIn("id_semester", $semester->pluck('id')->toArray())->delete();
            Matakuliah::whereIn("id", $multi_mk->pluck('id_mk')->toArray())->delete();
            Semester::whereIn("id_prodi", $prodi->pluck('id')->toArray())->delete();
            Prodi::where("id_fakultas", $id)->delete();
            Fakultas::where("id", $id)->delete();

            return redirect("/manageFakultas");
        } elseif ($fakultas == 1) {
            $id = $request->input("fakultas_id");
            $id_jenjang = $request->input("jenjang2");
            $nama = $request->input("nama_fakultas");
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
            $multi_mk = multi_mk::whereIn("id_semester", $semester->pluck('id')->toArray())->orderBy("id_mk", "asc")->get();
            $mk = Matakuliah::whereIn("id", $multi_mk->pluck('id_mk')->toArray())->orderBy("id", "asc")->get();
            $soal = Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $komentar = KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->get();
            $post = Post::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $komentar_post = KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->get();

            foreach ($komentar as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    foreach ($images as $image) {
                        Storage::delete('public/komentarSoal/' . $kmt->id_soal . '/' . $image);
                    }
                }
            }
            foreach ($post as $pst) {
                if ($pst->file_post) {
                    $images = json_decode($pst->file_post);
                    foreach ($images as $image) {
                        Storage::delete('public/post/' . $pst->id . '/' . $image);
                    }
                }
            }
            foreach ($soal as $soals) {
                if ($soals->tipe == 1) {
                    Storage::delete('public/pdf/' . $soals->isi_soal);
                } elseif ($soals->tipe == 2) {
                    Storage::delete('public/html/' . $soals->isi_soal);
                }
            }
            foreach ($komentar_post as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    foreach ($images as $image) {
                        Storage::delete('public/komentarPost/' . $kmt->id_post . '/' . $image);
                    }
                }
            }

            LovePost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            $komentarPost_id = $komentar_post->pluck('id');
            rating_komentar_post::whereIn("id_komentar", $komentarPost_id)->delete();
            KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            Post::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();
            $komentar_ids = $komentar->pluck('id');
            rating_komentar::whereIn('id_komentar', $komentar_ids)->delete();

            // ลบคอมเม้นที่เกี่ยวข้อง
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();

            // ลบคำถาม
            Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();

            // ลบ multi_mk, Matakuliah, Semester, และ Prodi
            multi_mk::whereIn("id_semester", $semester->pluck('id')->toArray())->delete();
            Matakuliah::whereIn("id", $multi_mk->pluck('id_mk')->toArray())->delete();
            Semester::where("id_prodi", $id)->delete();
            Prodi::where("id", $id)->delete();

            return redirect("/manageProdi");
        } elseif ($prodi == 1) {
            $id = $request->input("prodi_id");
            $id_fakultas = $request->input("fakultas2");
            $nama = $request->input("nama_prodi");
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
            $multi_mk = multi_mk::where("id_semester", $id)->orderBy("id_mk", "asc")->get();
            $mk = Matakuliah::where("id", $multi_mk)->orderBy("id", "asc")->get();
            $soal = Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $post = Post::whereIn("id_mk", $mk->pluck('id')->toArray())->orderBy("id", "asc")->get();
            $komentar = KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->get();
            $komentar_post = KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->get();

            foreach ($komentar as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    foreach ($images as $image) {
                        Storage::delete('public/komentarSoal/' . $kmt->id_soal . '/' . $image);
                    }
                }
            }
            foreach ($post as $pst) {
                if ($pst->file_post) {
                    $images = json_decode($pst->file_post);
                    foreach ($images as $image) {
                        Storage::delete('public/post/' . $pst->id . '/' . $image);
                    }
                }
            }
            foreach ($soal as $soals) {
                if ($soals->tipe == 1) {
                    Storage::delete('public/pdf/' . $soals->isi_soal);
                } elseif ($soals->tipe == 2) {
                    Storage::delete('public/html/' . $soals->isi_soal);
                }
            }
            foreach ($komentar_post as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    foreach ($images as $image) {
                        Storage::delete('public/komentarPost/' . $kmt->id_post . '/' . $image);
                    }
                }
            }

            LovePost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            $komentarPost_id = $komentar_post->pluck('id');
            rating_komentar_post::whereIn("id_komentar", $komentarPost_id)->delete();
            KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            Post::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();
            $komentar_ids = $komentar->pluck('id');
            rating_komentar::whereIn('id_komentar', $komentar_ids)->delete();

            // ลบคอมเม้นที่เกี่ยวข้อง
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();

            // ลบคำถาม
            Soal::whereIn("id_mk", $mk->pluck('id')->toArray())->delete();

            // ลบ multi_mk และ Matakuliah
            multi_mk::where("id_semester", $id)->delete();
            Matakuliah::where("id", $multi_mk)->delete();
            Semester::where("id", $id)->delete();

            return redirect("/manageSemester");
        } elseif ($semester == 1) {
            $id = $request->input("semester_id");
            $id_prodi = $request->input("prodi2");
            $nama = $request->input("nama_semester");
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
            $post = Post::where("id_mk", $id)->orderBy("id", "asc")->get();
            $komentar = KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->get();
            $komentar_post = KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->get();


            foreach ($post as $pst) {
                if ($pst->file_post) {
                    $images = json_decode($pst->file_post);
                    foreach ($images as $image) {
                        Storage::delete('public/post/' . $pst->id . '/' . $image);
                    }
                }
            }
            foreach ($komentar as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    foreach ($images as $image) {
                        Storage::delete('public/komentarSoal/' . $kmt->id_soal . '/' . $image);
                    }
                }
            }
            foreach ($soal as $soals) {
                if ($soals->tipe == 1) {
                    Storage::delete('public/pdf/' . $soals->isi_soal);
                } elseif ($soals->tipe == 2) {
                    Storage::delete('public/html/' . $soals->isi_soal);
                }
            }

            LovePost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            $komentarPost_id = $komentar_post->pluck('id');
            rating_komentar_post::whereIn("id_komentar", $komentarPost_id)->delete();
            KomentarPost::whereIn("id_post", $post->pluck('id')->toArray())->delete();
            Post::where("id_mk", $id)->delete();
            $komentar_ids = $komentar->pluck('id');
            rating_komentar::whereIn('id_komentar', $komentar_ids)->delete();
            KomentarSoal::whereIn("id_soal", $soal->pluck('id')->toArray())->delete();
            Soal::where("id_mk", $id)->delete();
            multi_mk::where("id_mk", $id)->delete();
            Matakuliah::where("id", $id)->delete();

            return redirect("/manageMatakuliah");
        } elseif ($matakuliah == 1) {
            $id = $request->input("matakuliah_id");
            $kode = $request->input("kode");
            $nama = $request->input("nama");
            $sks = $request->input("sks");
            $semesters = $request->input("semester2");
            Matakuliah::where('id', $id)->update([
                'kode' => $kode,
                'nama' => $nama,
                'sks' => $sks,
            ]);
            multi_mk::where("id_mk", $id)->delete();
            foreach ($semesters as $key => $semester) {
                multi_mk::create([
                    'id_mk' => $id,
                    'id_semester' => $semester,
                ]);
            }
            return redirect('/manageMatakuliah');
        } else {
            return redirect("/management");
        }
    }
    public function soalM(Request $request)
    {
        $soal = $request->input("edit");
        $id = $request->input("soal_id");

        $soals = Soal::whereIn("id", (array) $id)->get();
        if ($soal == 2) {
            // ดึงข้อมูลคอมเม้นที่เกี่ยวข้อง
            $komentar = KomentarSoal::whereIn("id_soal", (array) $id)->get();

            // ลบไฟล์รูปภาพที่เกี่ยวข้อง
            foreach ($komentar as $kmt) {
                if ($kmt->file_komentar) {
                    $images = json_decode($kmt->file_komentar);
                    if (is_array($images)) {
                        foreach ($images as $image) {
                            Storage::delete('public/komentarSoal/' . $kmt->id_soal . '/' . $image);
                        }
                    }
                }
            }

            foreach ($soals as $soal) {
                if ($soal->tipe == 1) {
                    Storage::delete('public/pdf/' . $soal->isi_soal);
                } elseif ($soal->tipe == 2) {
                    Storage::delete('public/html/' . $soal->isi_soal);
                }
            }



            // ลบเรตติ้งของคอมเม้นที่เกี่ยวข้อง
            $komentar_ids = $komentar->pluck('id');
            rating_komentar::whereIn('id_komentar', $komentar_ids)->delete();

            // ลบคอมเม้นที่เกี่ยวข้อง
            KomentarSoal::whereIn("id_soal", (array) $id)->delete();

            // ลบคำถาม
            Soal::whereIn("id", (array) $id)->delete();

            return redirect("/manageSoal");
        } elseif ($soal == 1) {
            return redirect('/manageSoal');
        } else {
            return redirect("/management");
        }
    }

    public function cariJenjangM(Request $request)
    {
        $id_jenjang = $request->input("jenjang");
        $jj = Jenjang::where("id", $id_jenjang)->orderBy("id", "asc")->firstOrFail();
        $fakultas = Fakultas::where("id_jenjang", $id_jenjang)->orderBy("id", "asc")->get();
        session()->put("jenjang_nama", $jj->nama);
    
        $html = view('partials.ManageFakultas', compact('fakultas'))->render();
        return response()->json(['html' => $html]);
    }
    

    public function cariFakultasM(Request $request)
    {
        $id_fakultas = $request->input("fakultas");
        session()->put("id_fakultas", $id_fakultas);
        $f = Fakultas::where("id", $id_fakultas)->orderBy("id", "asc")->firstOrFail();
        $prodi = Prodi::where("id_fakultas", $id_fakultas)->orderBy("id", "asc")->get();
        session()->put("fakultas_nama", $f->nama);
        
        $html = view('partials.ManageProdi', compact('prodi'))->render();
        return response()->json(['html' => $html]);
    }
    

    public function cariProdiM(Request $request)
    {
        $id_prodi = $request->input("prodi");
        $p = Prodi::where("id", $id_prodi)->firstOrFail();
        $semester = Semester::where("id_prodi", $id_prodi)->orderBy("id", "asc")->get();
        session()->put("prodi_nama", $p->nama);
        
        $html = view('partials.ManageSemester', compact('semester'))->render();
        return response()->json(['html' => $html]);
    }
    

    public function cariSemesterM(Request $request)
    {
        $id_semester = $request->input("semester");
        $s = Semester::where("id", $id_semester)->orderBy("id", "asc")->firstOrFail();
        $multi_mk = multi_mk::where("id_semester", $id_semester)->orderBy("id_mk", "asc")->get();
        $matakuliah = Matakuliah::whereIn("id", $multi_mk->pluck('id_mk')->toArray())->orderBy("id", "asc")->get();
        session()->put("semester_nama", $s->nama);
        // สร้าง view fragment
        $html = view('partials.ManageMatakuliah', compact('matakuliah'))->render();

        return response()->json(['html' => $html]);
    }

    public function cariMatakuliahM(Request $request)
    {
        $id_matakuliah = $request->input("matakuliah");
        $m = Matakuliah::where("id", $id_matakuliah)->orderBy("id", "asc")->firstOrFail();
        $soal = Soal::where("id_mk", $id_matakuliah)->orderBy("id", "asc")->get();
        session()->put("matakuliah_nama", $m->nama);
        // สร้าง view fragment
        $html = view('partials.ManageSoal', compact('soal'))->render();

        return response()->json(['html' => $html]);
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
        $data['multi_mk'] = multi_mk::where("id_semester", $request->id_semester)
            ->get(["id_mk"]);
        $data['matakuliah'] = Matakuliah::whereIn("id", $data['multi_mk']->pluck('id_mk')->toArray())
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
        $data['multi_mk'] = multi_mk::get(["id_mk", "id_semester"]);
        $data['soal'] = Soal::get(["nama_soal", "id"]);



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
        $data['multi_mk'] = multi_mk::get(["id_mk", "id_semester"]);
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
        $data['multi_mk'] = multi_mk::get(["id_mk", "id_semester"]);
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
        $data['multi_mk'] = multi_mk::get(["id_mk", "id_semester"]);


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
