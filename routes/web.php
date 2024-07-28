
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SoalsController;
use App\Http\Controllers\SearchSoalController;
use App\Http\Controllers\ManagementController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("homepage");
});

Route::get("/test", function () {
    return view("test");
});
Route::get("/thread", function () {
    return view("threadpage");
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

Route::get("/soal", [SoalsController::class, 'showsoal'])->name("tamplikansoal");
Route::post("/soal", [SoalsController::class, 'showsoal'])->name("pilihsoal");

Route::get("/lihatsoal", [SoalsController::class, 'lihatsoal'])->name("tamplikanhHsoal");
Route::post("/lihatsoal", [SoalsController::class, 'lihatsoal'])->name("pilihHsoal");
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


   //edit and delete data 
    Route::post("/manageJenjang", [ManagementController::class, 'jenjangM'])->name("jenjangM");
    Route::post("/manageFakultas", [ManagementController::class, 'fakultasM'])->name("fakultasM");
    Route::post("/manageProdi", [ManagementController::class, 'prodiM'])->name("prodiM");
    Route::post("/manageSemester", [ManagementController::class, 'semesterM'])->name("semesterM");
    Route::post("/manageMK", [ManagementController::class, 'matakuliahM'])->name("matakuliahM");
    Route::post("/manageSoal", [ManagementController::class, 'soalM'])->name("soalM");

});

Route::middleware(['guest'])->group(function () {
    Route::get("/login", function () {
        return view("login");
    });
    Route::post("/login", [AuthController::class, 'login'])->name("login");
});
