<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SoalsController;
use App\Http\Controllers\SearchSoalController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::get("/", function () {
    return view("homepage");
});

Route::get("/test", function () {
    return view("test");
});

Route::get("/register", function () {
    return view("register");
});
Route::post("/register", [AuthController::class, 'register'])->name("register");


Route::middleware(['auth'])->group(function () {


    Route::get("/search", [SearchSoalController::class, 'index'])->name("tampilkansoal");
    Route::post("/search", [SearchSoalController::class, 'cariJenjang'])->name("cariJenjang");
    Route::post("/search1", [SearchSoalController::class, 'cariFakultas'])->name("cariFakultas");
    Route::post("/search2", [SearchSoalController::class, 'cariProdi'])->name("cariProdi");
    Route::post("/search3", [SearchSoalController::class, 'cariSemester'])->name("cariSemester");
    Route::post("/search4", [SearchSoalController::class, 'cariMatakuliah'])->name("cariMatakuliah");

    Route::get("/soal/{matakuliah_id}", [SoalsController::class, 'showsoal'])->name("tamplikansoal");

    Route::get("/lihatsoal/{soal_id}", [SoalsController::class, 'lihatsoal'])->name("tamplikanhHsoal");
    Route::post("/komentar", [SoalsController::class, 'komentar']);
    Route::delete('/komentar/{id}', [SoalsController::class, 'deleteComment'])->name('komentar.delete');



    Route::post("/logout", [AuthController::class, 'logout'])->name("logout");

    Route::get("/manageJenjang", [ManagementController::class, 'indexMj'])->name("tampilkanJenjangM");
    Route::get("/manageFakultas", [ManagementController::class, 'indexMf'])->name("tampilkanFakultasM");
    Route::get("/manageProdi", [ManagementController::class, 'indexMp'])->name("tampilkanProdiM");
    Route::get("/manageSemester", [ManagementController::class, 'indexMsm'])->name("tampilkanSemesterM");
    Route::get("/manageMatakuliah", [ManagementController::class, 'indexMm'])->name("tampilkanMatakuliahM");
    Route::get("/manageSoal", [ManagementController::class, 'indexMs'])->name("tampilkanSoalM");


    Route::get("/management", [ManagementController::class, 'indexM'])->name("tampilkanM");
    Route::post("/management", [ManagementController::class, 'cariJenjangM'])->name("cariJenjangM");
    Route::post("/management1", [ManagementController::class, 'cariFakultasM'])->name("cariFakultasM");
    Route::post("/management2", [ManagementController::class, 'cariProdiM'])->name("cariProdiM");
    Route::post("/management3", [ManagementController::class, 'cariSemesterM'])->name("cariSemesterM");
    Route::post("/management4", [ManagementController::class, 'cariMatakuliahM'])->name("cariMatakuliahM");

    Route::post("/managementadd1", [ManagementController::class, 'cariFakultasM2'])->name("cariFakultasM2");
    Route::post("/managementadd2", [ManagementController::class, 'cariProdiM2'])->name("cariProdiM2");
    Route::post("/managementadd3", [ManagementController::class, 'cariSemesterM2'])->name("cariSemesterM2");
    Route::post("/managementadd4", [ManagementController::class, 'cariMatakuliahM2'])->name("cariMatakuliahM2");



    //add data
    Route::get("/addSoal", [ManagementController::class, 'indexMs2']);
    Route::post("/addSoal", [SoalsController::class, 'addSoal'])->name("tambahSoalM");
    Route::post("/addJenjang", [ManagementController::class, 'addJenjang'])->name("tambahJenjangM");
    Route::post("/addFakultas", [ManagementController::class, 'addFakultas'])->name("tambahFakultasM");
    Route::post("/addProdi", [ManagementController::class, 'addProdi'])->name("tambahProdiM");
    Route::post("/addSemester", [ManagementController::class, 'addSemester'])->name("tambahSemesterM");
    Route::post("/addMatakuliah", [ManagementController::class, 'addMatakuliah'])->name("tambahMatakuliahM");

    Route::post('/submit-rating', [SoalsController::class, 'submitRating'])->name('submit.rating');
    Route::post('/submit-rating-post', [PostController::class, 'submitRating'])->name('submit.rating.post');


    //edit and delete data 
    Route::post("/manageJenjang", [ManagementController::class, 'jenjangM'])->name("jenjangM");
    Route::post("/manageFakultas", [ManagementController::class, 'fakultasM'])->name("fakultasM");
    Route::post("/manageProdi", [ManagementController::class, 'prodiM'])->name("prodiM");
    Route::post("/manageSemester", [ManagementController::class, 'semesterM'])->name("semesterM");
    Route::post("/manageMK", [ManagementController::class, 'matakuliahM'])->name("matakuliahM");
    Route::post("/manageSoal", [ManagementController::class, 'soalM'])->name("soalM");



    Route::get('/thread/{id_mk}', [PostController::class, 'index'])->name('post.index');
    Route::post('/thread', [PostController::class, 'index'])->name('posts.index');
    Route::post('/post', [PostController::class, 'addPost'])->name('post.store');
    Route::get('/posts', [PostController::class, 'loadPosts'])->name('posts.load');
    Route::delete('/post/{id}', [PostController::class, 'destroyPost'])->name('post.destroy');
    Route::post('/komentar-post', [PostController::class, 'addKomentar'])->name('komentarPost.store');
    Route::delete('/komentar-post/{id}', [PostController::class, 'destroyKomentar'])->name('komentarPost.destroy');

    Route::post('/love/{id}', [PostController::class, 'addLove']);
    Route::post('/unlove/{id}', [PostController::class, 'destroyLove']);
    Route::post('/komentar-post/sort', [PostController::class, 'sortComments'])->name('komentar-post.sort');
    Route::get('/komentar-post/{post_id}', [PostController::class, 'loadComments'])->name('komentar-post.load');

    Route::delete('notifications/{id}', [NotificationController::class, 'deleteNotification'])->name('notification.delete');
    Route::post('/subscribe', [NotificationController::class, 'subscribe'])->name('subscribe');
    Route::get('/check-subscription/{mkId}', [NotificationController::class, 'checkSubscription'])->name('check.subscription');
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
   


});

Route::middleware(['guest'])->group(function () {
    Route::get("/login", function () {
        return view("login");
    });
    Route::post("/login", [AuthController::class, 'login'])->name("login");
});
