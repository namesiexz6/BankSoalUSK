@extends('navbar')
@section('body')

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .nav-link {
            font-size: 18px;
        }

        .navbar-brand img {
            width: 120px;
        }

        .w3-sidebar .w3-bar-item {
            font-size: 16px;
        }
    </style>
</head>

<body>
<div>
    <div class="w3-sidebar w3-dark-grey w3-bar-block " style="width:15%">
        <h3 class="w3-bar-item w3-light-grey" style="font-size: 30px; font-weight: 500; font-family: Montserrat, sans-serif;">
            {{ __('management.manajemen') }}</h3>
        
        @if(auth()->check() && auth()->user()->level == 1)
            <a href="/manageSoal" class="w3-bar-item w3-button">{{ __('management.manajemen_soal') }}</a>
            <a href="/manageMatakuliah" class="w3-bar-item w3-button">{{ __('management.manajemen_matakuliah') }}</a>
            <a href="/manageSemester" class="w3-bar-item w3-button">{{ __('management.manajemen_semester') }}</a>
            <a href="/manageProdi" class="w3-bar-item w3-button">{{ __('management.manajemen_prodi') }}</a>
            <a href="/manageFakultas" class="w3-bar-item w3-button">{{ __('management.manajemen_fakultas') }}</a>
            <a href="/manageJenjang" class="w3-bar-item w3-button">{{ __('management.manajemen_jenjang') }}</a>
        @elseif(auth()->check() && auth()->user()->level == 2)
            <a href="/manageSoal" class="w3-bar-item w3-button">{{ __('management.manajemen_soal') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_matakuliah') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_semester') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_prodi') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_fakultas') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_jenjang') }}</a>
        @else
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_soal') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_matakuliah') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_semester') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_prodi') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_fakultas') }}</a>
            <a href="#" class="w3-bar-item w3-button w3-disabled">{{ __('management.manajemen_jenjang') }}</a>
        @endif
    </div>
    
    @yield('content')
</div>
</body>
@endsection
