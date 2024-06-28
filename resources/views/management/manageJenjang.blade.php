@extends('management/management')
@section('content')

<head>

    <link rel="stylesheet" href="/css/popupform.css">
</head>
<div style="margin-left:15%">

    <div class="w3-container w3-light-blue">
        <h1>Manage Janjang</h1>
    </div>

    <div style="margin-left:10px; margin-right:10px;">
        <h2 class="mt-5">Daftar Janjang</h2>
        <table class="table table-bordered table-light table-striped my-3">
            <thead class="table-dark">
                <input type="hidden" name="id_fakultas" value="1">
                <tr>
                    <th colspan="5">Janjang dari Universitas Syiah Kuala</th>
                </tr>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Janjang</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>


                @foreach ($jenjang as $j)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $j->nama }}</td>
                    <td>


                        <div class="d-flex">

                            <button type="submit" name="edit" value="{{ $j->id }}" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;" onclick="openRegisterFormEdit('{{ $j->id }}','{{ $j->nama }}')">Edit</button>
                            <form action="{{ route('jenjangM')}}" method="post">
                                @csrf
                                <input type="hidden" name="jenjang_id" value="{{ $j->id }}">
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </form>
                        </div>

                    </td>
                </tr>
                @endforeach



            </tbody>
        </table>

        <button class="buttonadd mt-3" type="button" id="adressBTN" onclick="openRegisterForm()">Tambah Fakultas</button>
    </div>

    <div id="registerForm" class="register-form">

        <form action="{{ route('tambahJenjangM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Tambah Janjang</h1>
            <label class="form-label mt-3">Nama Janjang:</label>
            <input class="form-control" name="nama_jenjang" id="nama_jenjang" type="text" placeholder="MIPA" required>

            <button type="submit" class="registerbtn">Submit</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterForm()">Batal</button>
        </form>
    </div>
    <div id="registerFormEdit" class="register-form">

        <form action="{{ route('jenjangM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Edit Janjang</h1>
            <input type="hidden" name="edit" value="1">
            <label class="form-label mt-3">Nama Janjang:</label>
            <input type="hidden" name="jenjang_id" id="jenjang_id" value="">
            <input class="form-control" name="nama_jenjang" id="nama_jenjang" type="text" value="{{ $j->nama }}" required>

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

    function openRegisterFormEdit(id,   nama) {
        var form = document.getElementById("registerFormEdit");
        var jenjangIdInput = form.querySelector("input[name='jenjang_id'");
        var namaJenjangInput = form.querySelector("input[name='nama_jenjang'");
        jenjangIdInput.value = id;
        namaJenjangInput.value = nama; // กำหนดค่าให้
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