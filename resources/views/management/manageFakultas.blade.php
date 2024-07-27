@extends('management/management')
@section('content')

<head>

    <link rel="stylesheet" href="/css/popupform.css">
</head>
<div style="margin-left:15%">

    <div class="w3-container w3-light-blue">
        <h1>Manage Fakultas</h1>
    </div>

    <div class="w3-container">
        <div class="container">
            <form action="{{ route('cariJenjangM') }}" method="post" enctype="multipart/form-data">

                <div class="row">
                    <div class="col-md-3">

                        @csrf
                        <label for="jenjang" class="form-label mt-3">Pilih Jenjang:</label>
                        <select class="form-select" aria-label="Default select" name="jenjang" id="jenjang" onchange="this.form.submit()">
                            <option disabled selected value="">-- Pilih Janjang --</option>
                            @foreach ($jenjang as $jj)
                            <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>{{ $jj->nama }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </form>
        </div>
    </div>


    <div style="margin-left:10px; margin-right:10px;">
    @if(session('jenjang') != 0)
        <h2 class="mt-5">Daftar Soal</h2>
        <table class="table table-bordered table-light table-striped my-3">
            <thead class="table-dark">
                <input type="hidden" name="id_fakultas" value="1">
                <tr>
                    <th colspan="5">Janjang {{session('jenjang_nama')}}</th>
                </tr>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Fakultas</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>


                @foreach ($fakultas as $f)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $f->nama }}</td>
                    <td>
                        <div class="d-flex">

                            <button type="submit" name="edit" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;" onclick="openRegisterFormEdit('{{ $f->id }}','{{ $f->nama }}','{{ $f->id_jenjang }}')">Edit</button>
                            <form action="{{ route('fakultasM')}}" method="post">
                                @csrf
                                <input type="hidden" name="fakultas_id" value="{{ $f->id }}">
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach



            </tbody>
        </table>
        @endif

        <button class="buttonadd mt-3" type="button" id="adressBTN" onclick="openRegisterForm()">Tambah Fakultas</button>
    </div>

    <div id="registerForm" class="register-form">

        <form action="{{ route('tambahFakultasM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Tambah Mata kuliah</h1>
            <label class="form-label mt-3">Nama Fakultas:</label>
            <input class="form-control" name="nama_fakultas" id="nama_fakultas" type="text" placeholder="MIPA" required>

            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang2" required>
                <option value="">-- Pilih Janjang --</option>
                @foreach ($jenjang as $jj)
                <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>{{ $jj->nama }}</option>
                @endforeach
            </select>


            <button type="submit" class="registerbtn">Submit</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterForm()">Batal</button>
        </form>
    </div>
    <div id="registerFormEdit" class="register-form">

        <form action="{{ route('fakultasM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Edit Fakultas</h1>
            <input type="hidden" name="edit" value="1">
            <label class="form-label mt-3">Nama Fakultas:</label>
            <input type="hidden" name="fakultas_id" id="fakultas_id" value="">
            <input class="form-control" name="nama_fakultas" id="nama_fakultas" type="text" value="" required>

            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang2" required>
                <option value="">-- Pilih Janjang --</option>
                @foreach ($jenjang as $jj)
                <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>{{ $jj->nama }}</option>
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

    function openRegisterFormEdit(id, nama, id_jenjang) {
        var form = document.getElementById("registerFormEdit");
        var fakultasIdInput = form.querySelector("input[name='fakultas_id'");
        var namafakultasInput = form.querySelector("input[name='nama_fakultas'");
        var jenjangInput = form.querySelector("select[name='jenjang2'");
        jenjangInput.value = id_jenjang;
        fakultasIdInput.value = id;
        namafakultasInput.value = nama; // กำหนดค่าให้
        form.style.display = "block";
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

@endsection