@extends('navbar')
@section('body')

<head>
    <link rel="stylesheet" href="/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>


    <style>
        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .leftcolumn {
            float: left;
            width: 70%;
        }

        /* Right column */
        .rightcolumn {
            float: left;
            width: 30%;
            height: 100%;
            padding-left: 20px;
        }

        .card {
            background-color: white;
            padding: 20px;
            margin-top: 20px;
        }

        input[type=text] {
            border: none;
            background-color: transparent;
            border-bottom: 2px solid rgb(124, 122, 122);
        }

        @media screen and (max-width: 800px) {

            .leftcolumn,
            .rightcolumn {
                width: 100%;
                padding: 0;
            }
        }
    </style>
</head>


<div class="row">
    <div class="leftcolumn">
        <div class="card">
            @foreach ($soal as $soals)
            <h2>{{$soals->nama_soal}}</h2>
            <iframe src="/storage/pdf/{{$soals->isi_soal}}" height="800"></iframe>
            @endforeach
        </div>

    </div>
    <div class="rightcolumn">
        <div class="container mt-4">
            <h2>Comment</h2>
            <form class=" mt-2" action="/komentar" method="post">
                @csrf
                @foreach ($soal as $soals)
                <input type="hidden" name="soals_id" value="{{ $soals->id }}">
                @endforeach
    
                <textarea class="mb-3" name="isi_komentar" placeholder='Isi komentar'></textarea>
                <button type="submit" class="btn btn-info text-light">Kirim</button>
                <button type="reset" class="btn btn-info text-light" style="background-color: rgb(255, 123, 123);">Batal</button>
            </form>
        </div>
        @foreach ($komentar_soal as $komentar)
        <div class="container mt-2" style="background-color: white;">
            <span style="font-size: medium; font-weight: bold; ">{{$komentar->nama_komentar}}</span>
            <p>{{$komentar->isi_komentar}}</p>
            <form action="/komentar" method="post">
                @csrf
                <input type="hidden" name="komentar_id" value="{{$komentar->id}}" >
                <input type="hidden" name="soals_id" value="{{$komentar->id_soal}}">
                <button type="hapus" name="hapus" value="1" class="btn btn-link text-dark ml-auto" style="margin-left: 35ch;">Hapus</button>
            </form>
        </div>
        @endforeach
    </div>

</div>


@endsection