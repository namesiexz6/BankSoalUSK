@extends('navbar')
@section('body')
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<body>

    <!-- Sidebar -->
    <div class="w3-sidebar w3-dark-grey w3-bar-block" style="width:15%">
        <h3 class="w3-bar-item w3-light-grey">Manajemen</h3>
        <a href="/manageSoal" class="w3-bar-item w3-button">Manage Soal</a>
        <a href="/manageMatakuliah" class="w3-bar-item w3-button">Manage Matakuliah</a>
        <a href="/manageSemester" class="w3-bar-item w3-button">Manage Semester</a>
        <a href="/manageProdi" class="w3-bar-item w3-button">Manage Prodi</a>
        <a href="/manageFakultas" class="w3-bar-item w3-button">Manage Fakultas</a>
        <a href="/manageJenjang" class="w3-bar-item w3-button">Manage Janjang</a>
    </div>

    
    @yield('content')
</body>

</html>
@endsection