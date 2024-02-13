@extends('management/management')
@section('content')

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/popupform.css">
</head>


<div style="margin-left:15%">

    <div class="w3-container w3-light-blue">
        <h1>Manage Matakuliah</h1>
    </div>

    <div class="w3-container">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <form action="{{ route('cariJenjangM') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <label for="jenjang" class="form-label mt-3">Pilih Jenjang:</label>
                        <select class="form-select" aria-label="Default select" name="jenjang" id="jenjang" onchange="this.form.submit()">
                            <option hidden disabled selected value="{{ session('id_jenjang') }}"> {{session('jenjang')}}</option>
                            @foreach ($jenjang as $jj)
                            <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('cariFakultasM') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <label for="fakultas" class="form-label mt-3">Pilih Fakultas:</label>
                        @if(session('id_jenjang'))
                        <select class="form-select" aria-label="Default select" name="fakultas" id="fakultas" onchange="this.form.submit()">
                            <option hidden disabled selected value="{{ session('id_fakultas') }}"> {{session('fakultas')}}</option>
                            @foreach ($fakultas as $f)
                            @if($f->id_jenjang == session('id_jenjang'))
                            <option value="{{ $f->id }}">{{ $f->nama }}</option>
                            @endif
                            @endforeach
                        </select>
                        @else
                        <select class="form-select" aria-label="Default select" name="fakultas" id="fakultas" disabled>
                            <option selected>---</option>
                        </select>
                        @endif
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('cariProdiM') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <label for="prodi" class="form-label mt-3">Pilih Prodi:</label>
                        @if(session('id_fakultas'))
                        <select class="form-select" aria-label="Default select" name="prodi" id="prodi" onchange="this.form.submit()">
                            <option hidden disabled selected value="{{ session('id_prodi') }}"> {{session('prodi')}}</option>
                            @foreach ($prodi as $p)
                            @if($p->id_fakultas == session('id_fakultas'))
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endif
                            @endforeach
                        </select>
                        @else
                        <select class="form-select" aria-label="Default select" name="prodi" id="prodi" disabled>
                            <option selected>---</option>
                        </select>
                        @endif
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="{{ route('cariSemesterM') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <label for="semester" class="form-label mt-3">Pilih Semester:</label>
                        @if(session('id_prodi') )
                        <select class="form-select" aria-label="Default select" name="semester" id="semester" onchange="this.form.submit()">
                            <option hidden disabled selected value="{{ session('id_semester') }}"> {{session('semester')}}</option>
                            @foreach ($semester as $s)
                            @if($s->id_prodi == session('id_prodi'))
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endif
                            @endforeach
                        </select>
                        @else
                        <select class="form-select" aria-label="Default select" name="semester" id="semester" disabled>
                            <option selected>---</option>
                        </select>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div style="margin-left:10px; margin-right:10px;">

        @if(session('id_semester')!= 0)
        <h2 class="mt-5">Daftar Mata Kuliah</h2>
        <table class="table table-bordered table-light table-striped my-3 ">
            <thead class="table-dark">
                <input type="hidden" name="id_semester" value="1">
                <tr>
                    <th colspan="5">{{ session('semester') }}</th>
                </tr>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Kode</th>
                    <th scope="col">Mata Kuliah</th>
                    <th scope="col">SKS</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($matakuliah as $matakuliahs)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $matakuliahs->kode }}</td>
                    <td>{{ $matakuliahs->nama }}</td>
                    <td>{{ $matakuliahs->sks }}</td>
                    <td>
                        <form action="{{ route('matakuliahM')}}" method="post">
                            @csrf
                            <div class="d-flex">
                                <input type="hidden" name="matakuliah_id" value="{{ $matakuliahs->id }}">
                                <button type="submit" name="edit" value="1" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;">Edit</button>
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </div>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

        @endif
        <button class="buttonadd" type="button" id="adressBTN" onclick="openRegisterForm()">Tambah Mata kuliah</button>
    </div>

    <div id="registerForm" class="register-form">

        <form action="/register" method="post">

            @csrf
            <h1>Tambah Mata kuliah</h1>
            <label class="form-label mt-3">Nama Mata kuliah:</label>
            <input type="text" name="nama" placeholder="ex: Biologi" style="width: 100%;" required>
            <label class="form-label mt-3">Pili alamat:</label><br>
            <div id="addresses">
                <div class="adress">
                    
                    <select class="form-control" id="jenjang2">
                        <option value="">-- Pilih Janjang --</option>
                        @foreach ($jenjang as $jj)
                        <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                        @endforeach
                    </select>
                    <select class="form-control" id="fakultas2">
                        <option value="">-- Pilih Fakultas --</option>
                        @foreach ($fakultas as $f)
                        <option value="{{ $f->id }}">{{ $f->nama }}</option>
                        @endforeach
                    </select>
                    <select class="form-control" id="prodi2">
                        <option value="">-- Pilih Prodi --</option>
                        @foreach ($prodi as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    <select class="form-control" id="semester2">
                        <option value="">-- Pilih Semester --</option>
                        @foreach ($semester as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="button" onclick="addAddress()">Tambah Alamat</button>
            <button type="submit" class="registerbtn">Submit</button>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    function openRegisterForm() {
        document.getElementById("registerForm").style.display = "block";
    }

    function closeRegisterForm() {
        document.getElementById("registerForm").style.display = "none";
    }

    // ปิดฟอร์มเมื่อคลิกนอกพื้นที่ของป๊อปอัพ
    window.onclick = function(event) {
        var registerForm = document.getElementById("registerForm");
        if (event.target == registerForm) {
            registerForm.style.display = "none";
        }
    }

    function addAddress() {
        var container = document.getElementById("addresses");
        var addressInput = document.createElement("div");
        addressInput.innerHTML = '<select class="form-control" id="jenjang2"> <option value="">-- Pilih Janjang --</option> @foreach ($jenjang as $jj) <option value="{{ $jj->id }}">{{ $jj->nama }}</option> @endforeach </select> <select class="form-control" id="fakultas2"> <option value="">-- Pilih Fakultas --</option> @foreach ($fakultas as $f) <option value="{{ $f->id }}">{{ $f->nama }}</option> @endforeach </select> <select class="form-control" id="prodi2"> <option value="">-- Pilih Prodi --</option> @foreach ($prodi as $p) <option value="{{ $p->id }}">{{ $p->nama }}</option>@endforeach </select> <select class="form-control" id="semester2"> <option value="">-- Pilih Semester --</option> @foreach ($semester as $s) <option value="{{ $s->id }}">{{ $s->nama }}</option> @endforeach </select>';

        container.appendChild(addressInput);

    }
</script>
<script>
    $(document).ready(function() {
        $('#jenjang2').on('change', function() {
            var idCountry = this.value;
            $("#fakultas2").html('');
            $.ajax({
                url: "{{ route ('cariFakultasM2')}}",
                type: "POST",
                data: {
                    id_jenjang: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#fakultas2').html('<option value="">-- Pilih Fakultas --</option>');
                    console.log(result);
                    $.each(result.fakultas, function(key, value) {
                        $("#fakultas2").append('<option value="' + value.id + '">' + value.nama + '</option>');
                    });
                    $('#prodi2').html('<option value="">-- Pilih Prodi --</option>');
                }
            });
        });

        $('#fakultas2').on('change', function() {
            var idCountry = this.value;
            $("#prodi2").html('');
            $.ajax({
                url: "{{ route ('cariProdiM2')}}",
                type: "POST",
                data: {
                    id_fakultas: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#prodi2').html('<option value="">-- Pilih Prodi--</option>');
                    console.log(result);
                    $.each(result.prodi, function(key, value) {
                        $("#prodi2").append('<option value="' + value.id + '">' + value.nama + '</option>');
                    });
                    $('#semester2').html('<option value="">-- Pilih Semester --</option>');
                }
            });
        });
        $('#prodi2').on('change', function() {
            var idCountry = this.value;
            $("#semester2").html('');
            $.ajax({
                url: "{{ route ('cariSemesterM2')}}",
                type: "POST",
                data: {
                    id_prodi: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#semester2').html('<option value="">-- Pilih Semester --</option>');
                    console.log(result);
                    $.each(result.semester, function(key, value) {
                        $("#semester2").append('<option value="' + value.id + '">' + value.nama + '</option>');
                    });
                }
            });
        });
    });
</script>



@endsection