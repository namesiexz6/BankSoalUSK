@extends('navbar')
@section('body')

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .nav-link {
            font-size: 18px;
            /* ขนาดของฟอนต์ */
        }

        .navbar-brand img {
            width: 120px;
            /* ขนาดโลโก้ */
        }

        /* กำหนดสไตล์เฉพาะให้กับส่วน Sidebar */
        .w3-sidebar .w3-bar-item {
            font-size: 16px;
            /* ขนาดของฟอนต์ใน Sidebar */
        }
    </style>
</head>

<body>

<div>
    <div class="w3-sidebar w3-dark-grey w3-bar-block " style="width:15%">
        <h3 class="w3-bar-item w3-light-grey" style="font-size: 30px; font-weight: 500; font-family: Montserrat, sans-serif;">Manajemen</h3>
        @if(auth()->check() && auth()->user()->level == 1)
            <a href="/manageSoal" class="w3-bar-item w3-button">Manajemen Soal</a>
            <a href="/manageMatakuliah" class="w3-bar-item w3-button">Manajemen Matakuliah</a>
            <a href="/manageSemester" class="w3-bar-item w3-button">Manajemen Semester</a>
            <a href="/manageProdi" class="w3-bar-item w3-button">Manajemen Prodi</a>
            <a href="/manageFakultas" class="w3-bar-item w3-button">Manajemen Fakultas</a>
            <a href="/manageJenjang" class="w3-bar-item w3-button">Manajemen Janjang</a>
        @elseif(auth()->check() && auth()->user()->level == 2)
            <a href="/manageSoal" class="w3-bar-item w3-button">Manajemen Soal</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Matakuliah</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Semester</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Prodi</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Fakultas</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Janjang</a>
        @else
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Soal</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Matakuliah</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Semester</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Prodi</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Fakultas</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">Manajemen Janjang</a>
        @endif
    </div>
    @yield('content')

    </div>
</body>
@endsection