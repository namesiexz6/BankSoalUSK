@extends('management/management')
@section('content')

<head>
    <link rel="stylesheet" href="/css/popupform.css">
    <style>
        h1 {
            font-size: 25px;
            font-weight: 500;
            font-family: Montserrat, sans-serif;
        }

        h2 {
            font-size: 25px;
            font-weight: 500;
            font-family: Montserrat, sans-serif;
        }
    </style>
</head>
<div style="margin-left:15%">

<div class="background"
  style="background-image: url('{{  asset('background.png') }}'); background-size: cover; background-position: top; height: 10vh; display: flex; align-items: center; justify-content: center;">
  <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;">Manajemen Fakultas</h2>
</div>

    <div class="w3-container">
        <div class="container">
            <form id="jenjangForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="jenjang" class="form-label mt-3">Pilih Jenjang:</label>
                        <select class="form-select" aria-label="Default select" name="jenjang" id="jenjang">
                            <option value="">-- Pilih Jenjang --</option>
                            @foreach ($jenjang as $jj)
                                <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>
                                    {{ $jj->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div style="margin-left:10px; margin-right:10px;">
        <div id="fakultasContainer">
            <!-- เนื้อหาของ fakultas จะถูกอัพเดตที่นี่ -->
        </div>

        <button class="buttonadd mt-3" type="button" id="adressBTN" onclick="openRegisterForm()">Tambah Fakultas</button>
    </div>

    <div id="registerForm" class="register-form">
        <form action="{{ route('tambahFakultasM') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h1>Tambah Fakultas</h1>
            <label class="form-label mt-3">Nama Fakultas:</label>
            <input class="form-control" name="nama_fakultas" id="nama_fakultas" type="text" placeholder="ex: MIPA" required>
            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang2" required>
                <option value="">-- Pilih Janjang --</option>
                @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="registerbtn">Submit</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterForm()">Batal</button>
        </form>
    </div>

    <div id="registerFormEdit" class="register-form">
        <form action="{{ route('fakultasM') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="edit" value="1">
            <h1>Edit Fakultas</h1>
            <label class="form-label mt-3">Nama Fakultas:</label>
            <input type="hidden" name="fakultas_id" id="fakultas_id" value="">
            <input class="form-control" name="nama_fakultas" id="nama_fakultas" type="text" value="" required>
            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang3" required>
                <option value="">-- Pilih Janjang --</option>
                @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="registerbtn">Submit</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterFormEdit()">Batal</button>
        </form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        let jenjang = $('#jenjang');
        let jenjangForm = $('#jenjangForm');
        let csrfToken = '{{ csrf_token() }}';

        jenjang.change(function() {
            let idJenjang = this.value;
            if (idJenjang) {
                $.ajax({
                    url: "{{ route('cariJenjangM') }}",
                    type: "POST",
                    data: {
                        jenjang: idJenjang,
                        _token: csrfToken
                    },
                    success: function(result) {
                        $('#fakultasContainer').html(result.html);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }
        });
    });

    function openRegisterForm() {
        document.getElementById("registerForm").style.display = "block";
    }

    function closeRegisterForm() {
        document.getElementById("registerForm").style.display = "none";
    }

    function openRegisterFormEdit(id, nama, id_jenjang) {
        var form = document.getElementById("registerFormEdit");
        var fakultasIdInput = form.querySelector("input[name='fakultas_id']");
        var namafakultasInput = form.querySelector("input[name='nama_fakultas']");
        var jenjangInput = form.querySelector("select[name='jenjang2']");
        jenjangInput.value = id_jenjang;
        fakultasIdInput.value = id;
        namafakultasInput.value = nama;
        form.style.display = "block";
    }

    function closeRegisterFormEdit() {
        document.getElementById("registerFormEdit").style.display = "none";
    }

    function confirmDelete() {
        return confirm('Are you sure you want to delete this item?');
    }
</script>
@endsection
