@extends('management/management')
@section('content')

<head>

    <link rel="stylesheet" href="/css/popupform.css">
</head>

<div style="margin-left:15%">

    <div class="w3-container w3-light-blue">
        <h1>Manage Prodi</h1>
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

            </div>
        </div>
    </div>
    <div style="margin-left:10px; margin-right:10px;">
        @if(session('id_fakultas')!= 0)

        <h2 class="mt-5">Daftar Soal</h2>
        <table class="table table-bordered table-light table-striped my-3">
            <thead class="table-dark">
                <input type="hidden" name="id_fakultas" value="1">
                <tr>
                    <th colspan="5">Fakultas {{session('fakultas')}}</th>
                </tr>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Prodi</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>


                @foreach ($prodi as $p)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $p->nama }}</td>
                    <td>
                        <div class="d-flex">

                            <button type="submit" name="edit" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;" onclick="openRegisterFormEdit('{{ $p->id }}','{{ $p->nama }}','{{ $p->id_fakultas }}')">Edit</button>
                            <form action="{{ route('prodiM')}}" method="post">
                                @csrf
                                <input type="hidden" name="prodi_id" value="{{ $p->id }}">
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach



            </tbody>
        </table>
        @endif

        <button class="buttonadd mt-3" type="button" id="adressBTN" onclick="openRegisterForm()">Tambah Prodi</button>
        
    </div>

    <div id="registerForm" class="register-form">

        <form action="{{ route('tambahProdiM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Tambah Prodi</h1>
            <label class="form-label mt-3">Nama Prodi:</label>
            <input class="form-control" name="nama_prodi" id="nama_prodi" type="text" placeholder="Informatika" required>
            <label class="form-label mt-3">Pilih alamat</label><br>
            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang2">
                <option value="">-- Pilih Janjang --</option>
                @foreach ($jenjang as $jj)
                <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="fakultas2" id="fakultas2">
                <option value="">-- Pilih Fakultas --</option>
                @foreach ($fakultas as $f)
                <option value="{{ $f->id }}">{{ $f->nama }}</option>
                @endforeach
            </select>

            <button type="submit" class="registerbtn">Submit</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterForm()">Batal</button>
        </form>
    </div>
    
    <div id="registerFormEdit" class="register-form">

        <form action="{{ route('editProdiM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Edit Prodi</h1>
            <label class="form-label mt-3">Nama Prodi:</label>
            <input type="hidden" name="prodi_id" id="prodi_id" value="">
            <input class="form-control" name="nama_prodi" id="nama_prodi" type="text" value="" required>
            <label class="form-label mt-3">Pilih alamat</label><br>
            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang3">
                <option value="">-- Pilih Janjang --</option>
                @foreach ($jenjang as $jj)
                <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="fakultas2" id="fakultas3">
                <option value="">-- Pilih Fakultas --</option>
                @foreach ($fakultas as $f)
                <option value="{{ $f->id }}">{{ $f->nama }}</option>
                @endforeach
            </select>

            <button type="submit" class="registerbtn">Submit</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterFormEdit()">Batal</button>
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

    function openRegisterFormEdit(id, nama, id_fakultas) {
        var form = document.getElementById("registerFormEdit");
        var prodiIdInput = form.querySelector("input[name='prodi_id'");
        var namaprodiInput = form.querySelector("input[name='nama_prodi'");
        var fakultasInput = form.querySelector("select[name='fakultas2'");
        fakultasInput.value = id_fakultas;
        prodiIdInput.value = id;
        namaprodiInput.value = nama; // กำหนดค่าให้
        form.style.display = "block";
       ;
    }

    function closeRegisterFormEdit() {
        document.getElementById("registerFormEdit").style.display = "none";
    }
    // ปิดฟอร์มเมื่อคลิกนอกพื้นที่ของป๊อปอัพ
    window.onclick = function(event) {
        var registerFormEdit = document.getElementById("registerFormEdit");
        if (event.target == registerFormEdit) {
            registerFormEdit.style.display = "none";
        }
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

    });
</script>
<script>
    $(document).ready(function() {
        $('#jenjang3').on('change', function() {
            var idCountry = this.value;
            $("#fakultas3").html('');
            $.ajax({
                url: "{{ route ('cariFakultasM2')}}",
                type: "POST",
                data: {
                    id_jenjang: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#fakultas3').html('<option value="">-- Pilih Fakultas --</option>');
                    console.log(result);
                    $.each(result.fakultas, function(key, value) {
                        $("#fakultas3").append('<option value="' + value.id + '">' + value.nama + '</option>');
                    });
                    $('#prodi3').html('<option value="">-- Pilih Prodi --</option>');
                }
            });
        });
    });
    </script>
@endsection