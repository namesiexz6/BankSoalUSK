<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\rating_komentar_post;
use App\Models\User;
use App\Models\KomentarPost;
use App\Models\LovePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
{
    $id_mk = $request->input("id_mk");

    session()->put("id_mk", $id_mk);
    $posts = Post::where("id_mk", $id_mk)->orderBy("id", "asc")->get();
    $post_ids = $posts->pluck('id'); 

    // รับพารามิเตอร์การเรียงลำดับจาก request
    $sort = $request->input('sort', 'newest'); // ค่าเริ่มต้นคือ 'newest'

    // จัดการการเรียงลำดับตามพารามิเตอร์
    if ($sort == 'newest') {
        $komentar_post = KomentarPost::whereIn("id_post", $post_ids)->orderBy("created_at", "desc")->get();
    } elseif ($sort == 'oldest') {
        $komentar_post = KomentarPost::whereIn("id_post", $post_ids)->orderBy("created_at", "asc")->get();
    } elseif ($sort == 'most_rated') {
        $komentar_post = KomentarPost::whereIn("id_post", $post_ids)
            ->leftJoin('rating_komentar_post', 'komentar_post.id', '=', 'rating_komentar_post.id_komentar')
            ->selectRaw('komentar_post.*, COUNT(rating_komentar_post.id) as rating_count')
            ->groupBy('komentar_post.id', 'komentar_post.id_post', 'komentar_post.id_user', 'komentar_post.parent_id', 'komentar_post.isi_komentar', 'komentar_post.file_komentar', 'komentar_post.created_at', 'komentar_post.updated_at')
            ->orderBy('rating_count', 'desc')
            ->get();
    }
    $user_id = $posts->pluck('id_user')->unique();
    $userPost = User::whereIn("id", $user_id)->orderBy("id", "asc")->get()->keyBy('id');

    $user_ids = $komentar_post->pluck('id_user')->unique();
    $user = User::whereIn("id", $user_ids)->orderBy("id", "asc")->get()->keyBy('id');

    $usernow = Auth::user();

    $ratings = rating_komentar_post::where('id_user', $usernow->id)
        ->whereIn('id_komentar', $komentar_post->pluck('id'))
        ->get()
        ->keyBy('id_komentar');

    $ratingData = rating_komentar_post::selectRaw('id_komentar, AVG(rating) as avg_rating, COUNT(id) as rating_count')
        ->whereIn('id_komentar', $komentar_post->pluck('id'))
        ->groupBy('id_komentar')
        ->get()
        ->keyBy('id_komentar');

    $komentar_parents = $komentar_post->whereNull('parent_id');
    $komentar_replies = $komentar_post->whereNotNull('parent_id')->groupBy('parent_id');

    if ($request->ajax()) {
        return view('partials.comments', compact('komentar_parents', 'komentar_replies', 'user', 'ratings', 'ratingData', 'sort'))->render();
    }

    return view("threadpage", compact('posts', 'komentar_parents', 'komentar_replies', 'user','userPost', 'usernow', 'ratings', 'ratingData', 'sort'));
}


    public function addPost(Request $request)
    {
        $request->validate([
            'id_mk' => 'required|exists:matakuliah,id',
            'isi_post' => 'required|string|max:255',
            'file_post.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $post = new Post();
        $post->id_mk = $request->id_mk;
        $post->id_user = Auth::id();
        $post->isi_post = $request->isi_post;
        $post->save();
        $images = [];
        if ($request->hasfile('file_post')) {
            $destinationPath = 'public/post/' . $post->id_mk;
            $i = 1;
            foreach ($request->file('file_post') as $file) {
                $filename = $post->id . '_' . $post->id_user . '_' . $i . '.' . $file->getClientOriginalExtension();
                $file->storeAs($destinationPath, $filename);
                $images[] = $filename;
                $i++;
            }
            $post->file_post = json_encode($images);
        }
    
        $post->save();
    
        return response()->json(['success' => true]);
    }

    public function destroyPost($id)
    {
        $post = Post::findOrFail($id);
        if ($post->id_user != Auth::id() && Auth::user()->level != 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['success' => true]);
    }
    public function addKomentar(Request $request)
    {
        $request->validate([
            'id_post' => 'required|exists:posts,id',
            'isi_komentar' => 'required|string|max:255',
            'file_komentar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:komentar_post,id',
        ]);

        $komentar = new KomentarPost();
        $komentar->id_post = $request->id_post;
        $komentar->id_user = Auth::id();
        $komentar->isi_komentar = $request->isi_komentar;
        if ($request->filled('parent_id')) {
            $komentar->parent_id = $request->parent_id;
        }
        $komentar->save();

        $images = [];
        if ($request->hasfile('file_komentar')) {
            $destinationPath = 'public/komentarPost/' . $komentar->id_post;
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

    public function destroyKomentar($id)
    {
        $komentar = KomentarPost::findOrFail($id);
        if ($komentar->id_user != Auth::id() && Auth::user()->level != 1) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $komentar->delete();

        return response()->json(['success' => true]);
    }

    public function addlove($id)
    {
        $post = Post::findOrFail($id);
        $love = LovePost::where('id_post', $id)->where('id_user', Auth::id())->first();

        if ($love) {
            $love->delete();
        } else {
            LovePost::create([
                'id_post' => $id,
                'id_user' => Auth::id(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function destroyLove($id)
    {
        $love = LovePost::where('id_post', $id)->where('id_user', Auth::id())->first();

        if ($love) {
            $love->delete();
        }

        return response()->json(['success' => true]);
    }
}
