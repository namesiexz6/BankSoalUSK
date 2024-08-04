<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\rating_komentar_post;
use App\Models\User;
use App\Models\KomentarPost;
use App\Models\LovePost;
use App\Models\NotificationSubscription;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request, $id_mk)
    {
        if ($id_mk == null) {
            $id_mk = session()->get("id_matakuliah");
        }
        session()->put("id_matakuliah", $id_mk);
        $posts = Post::where("id_mk", $id_mk)
            ->with('loves') // Eager load the loves relationship
            ->orderBy("id", "asc")
            ->get();

        $post_ids = $posts->pluck('id');
        if ($post_ids == null) {
            $post_ids = session()->get("id_post");
        }
        session()->put("id_post", $post_ids);

        $commentSort = $request->input('sort', 'newest');

        $sort = $request->input('sort', 'newest');

        if ($sort == 'newest') {
            $posts = Post::where("id_mk", $id_mk)->orderBy("created_at", "desc")->get();
        } elseif ($sort == 'oldest') {
            $posts = Post::where("id_mk", $id_mk)->orderBy("created_at", "asc")->get();
        } elseif ($sort == 'most_rated') {
            $posts = Post::where("id_mk", $id_mk)
                ->leftJoin('love_post', 'post.id', '=', 'love_post.id_post')
                ->selectRaw('post.*, COUNT(love_post.id) as love_count')
                ->groupBy('post.id', 'post.id_mk', 'post.id_user', 'post.isi_post', 'post.file_post', 'post.created_at', 'post.updated_at')
                ->orderBy('love_count', 'desc')
                ->get();
        }
        $komentar_post = $this->sortCommentsQuery($post_ids, $commentSort);

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
            return view('partials.posts', compact('posts', 'komentar_parents', 'komentar_replies', 'user', 'userPost', 'usernow', 'ratings', 'ratingData', 'commentSort', 'sort'))->render();
        }

        return view("threadpage", compact('posts', 'komentar_parents', 'komentar_replies', 'user', 'userPost', 'usernow', 'ratings', 'ratingData', 'commentSort', 'sort'));
    }

    private function sortCommentsQuery($post_ids, $commentSort)
    {
        if ($commentSort == 'newest') {
            return KomentarPost::whereIn("id_post", $post_ids)->orderBy("created_at", "desc")->get();
        } elseif ($commentSort == 'oldest') {
            return KomentarPost::whereIn("id_post", $post_ids)->orderBy("created_at", "asc")->get();
        } elseif ($commentSort == 'most_rated') {
            return KomentarPost::whereIn("id_post", $post_ids)
                ->leftJoin('rating_komentar_post', 'komentar_post.id', '=', 'rating_komentar_post.id_komentar')
                ->selectRaw('komentar_post.*, COUNT(rating_komentar_post.id) as rating_count')
                ->groupBy('komentar_post.id', 'komentar_post.id_post', 'komentar_post.id_user', 'komentar_post.parent_id', 'komentar_post.isi_komentar', 'komentar_post.file_komentar', 'komentar_post.created_at', 'komentar_post.updated_at')
                ->orderBy('rating_count', 'desc')
                ->get();
        }
    }

    public function sortComments(Request $request)
    {
        $post_id = $request->input('post_id');
        $sort = $request->input('sort', 'newest');
        $comments = $this->sortCommentsQuery([$post_id], $sort);
        $usernow = Auth::user();
        $user_ids = $comments->pluck('id_user')->unique();
        $user = User::whereIn('id', $user_ids)->get()->keyBy('id');
        $ratings = rating_komentar_post::where('id_user', $usernow->id)
            ->whereIn('id_komentar', $comments->pluck('id'))
            ->get()
            ->keyBy('id_komentar');

        $ratingData = rating_komentar_post::selectRaw('id_komentar, AVG(rating) as avg_rating, COUNT(id) as rating_count')
            ->whereIn('id_komentar', $comments->pluck('id'))
            ->groupBy('id_komentar')
            ->get()
            ->keyBy('id_komentar');

        $komentar_parents = $comments->whereNull('parent_id');
        $komentar_replies = $comments->whereNotNull('parent_id')->groupBy('parent_id');

        $commentsHtml = view('partials.commentPost', compact('komentar_parents', 'komentar_replies', 'user', 'ratings', 'ratingData', 'post_id'))->render();

        return response()->json(['success' => true, 'commentsHtml' => $commentsHtml]);
    }

    public function loadComments($post_id)
    {
        $comments = KomentarPost::where('id_post', $post_id)->orderBy('created_at', 'desc')->get();

        $user_ids = $comments->pluck('id_user')->unique();
        $user = User::whereIn('id', $user_ids)->get()->keyBy('id');
        $usernow = Auth::user();
        $ratings = rating_komentar_post::where('id_user', $usernow->id)
            ->whereIn('id_komentar', $comments->pluck('id'))
            ->get()
            ->keyBy('id_komentar');
        $ratingData = rating_komentar_post::selectRaw('id_komentar, AVG(rating) as avg_rating, COUNT(id) as rating_count')
            ->whereIn('id_komentar', $comments->pluck('id'))
            ->groupBy('id_komentar')
            ->get()
            ->keyBy('id_komentar');

        $komentar_parents = $comments->whereNull('parent_id');
        $komentar_replies = $comments->whereNotNull('parent_id')->groupBy('parent_id');

        $commentsHtml = view('partials.commentPost', compact('komentar_parents', 'komentar_replies', 'user', 'ratings', 'ratingData', 'post_id'))->render();

        return response()->json(['success' => true, 'commentsHtml' => $commentsHtml]);
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

        $id_mk = $request->id_mk;
        // ดึงรายการผู้ใช้ที่ subscribe
        $subscribers = NotificationSubscription::where('id_mk', $id_mk)->orderBy("id", "asc")->get();
        $mataKuliahNama = $post->mataKuliah->nama;  // ดึงชื่อ MataKuliah

        // ส่งการแจ้งเตือนให้กับผู้ใช้ที่ subscribe
        foreach ($subscribers as $subscriber) {
            if ($subscriber->user_id != Auth::id()) {
                Notification::create([
                    'user_id' => $subscriber->user_id,
                    'type' => 'new_post',
                    'data' => json_encode([
                        'message' => 'Ada postingan baru di thread mata kuliah ' . $mataKuliahNama . ': ' . $post->isi_post,
                        'post_id' => $post->id,
                        'url' => route('post.index', $id_mk).'#post-'.$post->id,
                    ]),
                ]);
            }
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

        // Delete all associated loves
        LovePost::where('id_post', $id)->delete();

        // Delete all associated comments and their related ratings and files
        $comments = KomentarPost::where('id_post', $id)->get();
        foreach ($comments as $comment) {
            // Delete ratings associated with the comment
            rating_komentar_post::where('id_komentar', $comment->id)->delete();

            // Delete comment files
            $this->deleteCommentFiles($comment);

            // Delete child comments
            $childComments = KomentarPost::where('parent_id', $comment->id)->get();
            foreach ($childComments as $childComment) {
                rating_komentar_post::where('id_komentar', $childComment->id)->delete();
                $this->deleteCommentFiles($childComment);
                $childComment->delete();
            }

            // Delete the comment
            $comment->delete();
        }

        // Delete post files
        $this->deletePostFiles($post);

        // Delete the post
        $post->delete();

        return response()->json(['success' => true]);
    }
    private function deletePostFiles($post)
    {
        if ($post->file_post) {
            $images = json_decode($post->file_post);
            foreach ($images as $image) {
                $path = 'public/post/' . $post->id_mk . '/' . $image;
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
        }
    }
    public function addKomentar(Request $request)
    {
        $request->validate([
            'id_post' => 'required|exists:post,id',
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
        $id_mk = Post::find($request->id_post)->id_mk;

        $owner = Post::find($request->id_post)->id_user;
        if ($owner != Auth::id()) {
            $namaKomen = Auth::user()->nama;
            Notification::create([
                'user_id' => $owner,
                'type' => 'new_comment',
                'data' => json_encode([
                    'message' => $namaKomen . ' telah memberikan komentar pada postingan anda : ' . $komentar->isi_komentar,
                    'post_id' => $request->id_post,
                    'comment_id' => $komentar->id,
                    'url' => route('post.index', $id_mk).'#post-'.$request->id_post,
                ]),
            ]);
        }
        if ($request->filled('parent_id')) {
            $parent = KomentarPost::find($request->parent_id);
            $owner = $parent->id_user;
            if ($owner != Auth::id()) {
                $namaKomen = Auth::user()->nama;
                Notification::create([
                    'user_id' => $owner,
                    'type' => 'new_reply',
                    'data' => json_encode([
                        'message' => $namaKomen . ' membalas komen anda : ' . $komentar->isi_komentar,
                        'post_id' => $request->id_post,
                        'comment_id' => $komentar->id,
                        'url' => route('post.index', $id_mk).'#post-'.$request->id_post,
                    ]),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function destroyKomentar($id)
    {
        $komentar = KomentarPost::find($id);
        $id_post = $komentar->id_post;

        if ($komentar) {
            // ลบเรตติ้งของคอมเม้น
            rating_komentar_post::where('id_komentar', $id)->delete();

            // ค้นหาคอมเม้นลูกทั้งหมด
            $childComments = KomentarPost::where('parent_id', $id)->get();

            // ลบคอมเม้นลูกทั้งหมด
            foreach ($childComments as $childComment) {
                rating_komentar_post::where('id_komentar', $childComment->id)->delete();
                $this->deleteCommentFiles($childComment);
                $childComment->delete();
            }

            // ลบไฟล์ภาพของคอมเม้นหลัก
            $this->deleteCommentFiles($komentar);

            // ลบคอมเม้นหลัก
            $komentar->delete();

            return response()->json(['success' => true, 'id_post' => $id_post]);
        } else {
            return response()->json(['success' => false, 'message' => 'Comment not found']);
        }
    }
    private function deleteCommentFiles($komentar)
    {
        if ($komentar->file_komentar) {
            $images = json_decode($komentar->file_komentar);
            foreach ($images as $image) {
                $path = 'public/komentarPost/' . $komentar->id_post . '/' . $image;
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
        }
    }

    public function addLove($id)
    {
        $post = Post::findOrFail($id);
        $love = LovePost::where('id_post', $id)->where('id_user', Auth::id())->first();

        if ($love) {
            $love->delete();
            $action = 'unloved';
        } else {
            LovePost::create([
                'id_post' => $id,
                'id_user' => Auth::id(),
            ]);
            $action = 'loved';
        }

        $loveCount = $post->loves->count();

        return response()->json(['success' => true, 'action' => $action, 'post_id' => $id, 'love_count' => $loveCount]);
    }

    public function destroyLove($id)
    {
        $love = LovePost::where('id_post', $id)->where('id_user', Auth::id())->first();

        if ($love) {
            $love->delete();
        }

        return response()->json(['success' => true, 'post_id' => $id]);
    }

    public function submitRating(Request $request)
    {
        $request->validate([
            'id_komentar' => 'required|exists:komentar_post,id',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $rating = rating_komentar_post::updateOrCreate(
            [
                'id_komentar' => $request->id_komentar,
                'id_user' => Auth::id()
            ],
            [
                'rating' => $request->rating
            ]
        );

        $komentar = KomentarPost::find($request->id_komentar);
        $avgRating = rating_komentar_post::where('id_komentar', $request->id_komentar)
            ->avg('rating');
        $ratingCount = rating_komentar_post::where('id_komentar', $request->id_komentar)
            ->count();

        return response()->json([
            'success' => true,
            'avg_rating' => number_format($avgRating, 1),
            'rating_count' => $ratingCount,
            'komentar_id' => $request->id_komentar
        ]);
    }
}
