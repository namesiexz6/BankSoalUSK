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
  <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;">{{ __('management.manajemen_semester') }}</h2>
</div>

    <div class="w3-container">
        <div class="container">
            <form id="prodiForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="jenjang" class="form-label mt-3">{{ __('management.fakultas') }}:</label>
                        <select class="form-select" aria-label="Default select" name="jenjang" id="jenjang">
                            <option value="">-- {{ __('management.fakultas') }} --</option>
                            @foreach ($jenjang as $jj)
                                <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>{{ $jj->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fakultas" class="form-label mt-3">{{ __('management.fakultas') }}:</label>
                        <select class="form-select" aria-label="Default select" name="fakultas" id="fakultas" {{ !session('jenjang') ? 'disabled' : '' }}>
                            <option value="">-- {{ __('management.fakultas') }} --</option>
                            @foreach ($fakultas as $f)
                                <option value="{{ $f->id }}" {{ session('fakultas') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="prodi" class="form-label mt-3">{{ __('management.prodi') }}:</label>
                        <select class="form-select" aria-label="Default select" name="prodi" id="prodi" {{ !session('fakultas') ? 'disabled' : '' }}>
                            <option disabled selected value="">-- {{ __('management.prodi') }} --</option>
                            @foreach ($prodi as $p)
                                <option value="{{ $p->id }}" {{ session('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div style="margin-left:10px; margin-right:10px;">
        <div id="semesterContainer">
            <!-- เนื้อหาของ semester จะถูกอัพเดตที่นี่ -->
        </div>

        <button class="buttonadd mt-3" type="button" id="adressBTN" onclick="openRegisterForm()">{{ __('management.tambah_semester') }}</button>
    </div>

    <div id="registerForm" class="register-form">
        <form action="{{ route('tambahSemesterM') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h1>{{ __('management.tambah_semester') }}</h1>
            <label class="form-label mt-3">{{ __('management.semester_name') }}:</label>
            <input class="form-control" name="nama_semester" id="nama_semester" type="text" placeholder="ex: Semester 1" required>
            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang2">
                <option value="">-- {{ __('management.jenjang') }} --</option>
                @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="fakultas2" id="fakultas2">
                <option value="">-- {{ __('management.fakultas') }} --</option>
                @foreach ($fakultas as $f)
                    <option value="{{ $f->id }}">{{ $f->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="prodi2" id="prodi2" required>
                <option value="">-- {{ __('management.prodi') }} --</option>
                @foreach ($prodi as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="registerbtn">{{ __('management.submit') }}</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterForm()">{{ __('management.batal') }}</button>
        </form>
    </div>

    <div id="registerFormEdit" class="register-form">
        <form action="{{ route('semesterM') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="edit" value="1">
            <h1>{{ __('management.edit') }} {{ __('management.semester_name') }}</h1>
            <label class="form-label mt-3">{{ __('management.semester_name') }}:</label>
            <input type="hidden" name="semester_id" id="semester_id" value="">
            <input class="form-control" name="nama_semester" id="nama_semester" type="text" value="" required>
            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang3">
                <option value="">-- {{ __('management.jenjang') }} --</option>
                @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="fakultas2" id="fakultas3">
                <option value="">-- {{ __('management.fakultas') }} --</option>
                @foreach ($fakultas as $f)
                    <option value="{{ $f->id }}">{{ $f->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="prodi2" id="prodi3" required>
                <option value="">-- {{ __('management.prodi') }} --</option>
                @foreach ($prodi as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="registerbtn">{{ __('management.submit') }}</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterFormEdit()">{{ __('management.batal') }}</button>
        </form>
    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        let jenjang = $('#jenjang');
        let fakultas = $('#fakultas');
        let prodi = $('#prodi');
        let csrfToken = '{{ csrf_token() }}';

        jenjang.change(function() {
            let idJenjang = this.value;
            if (idJenjang) {
                fakultas.prop('disabled', false);
                $.ajax({
                    url: "{{ route('cariFakultasM2') }}",
                    type: "POST",
                    data: {
                        id_jenjang: idJenjang,
                        _token: csrfToken
                    },
                    dataType: 'json',
                    success: function(result) {
                        fakultas.empty().append('<option value="">-- {{ __('management.pilih_fakultas') }} --</option>');
                        $.each(result.fakultas, function(key, value) {
                            fakultas.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                        prodi.prop('disabled', true);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            } else {
                fakultas.prop('disabled', true);
                prodi.prop('disabled', true);
            }
        });

        fakultas.change(function() {
            let idFakultas = this.value;
            if (idFakultas) {
                prodi.prop('disabled', false);
                $.ajax({
                    url: "{{ route('cariProdiM2') }}",
                    type: "POST",
                    data: {
                        id_fakultas: idFakultas,
                        _token: csrfToken
                    },
                    dataType: 'json',
                    success: function(result) {
                        prodi.empty().append('<option value="">-- {{ __('management.pilih_prodi') }} --</option>');
                        $.each(result.prodi, function(key, value) {
                            prodi.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            } else {
                prodi.prop('disabled', true);
            }
        });

        prodi.change(function() {
            let idProdi = this.value;
            if (idProdi) {
                $.ajax({
                    url: "{{ route('cariProdiM') }}",
                    type: "POST",
                    data: {
                        prodi: idProdi,
                        _token: csrfToken
                    },
                    success: function(result) {
                        $('#semesterContainer').html(result.html);
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

    function openRegisterFormEdit(id, nama, id_prodi) {
        var form = document.getElementById("registerFormEdit");
        var semesterIdInput = form.querySelector("input[name='semester_id']");
        var namasemesterInput = form.querySelector("input[name='nama_semester']");
        var prodiInput = form.querySelector("select[name='prodi2']");
        prodiInput.value = id_prodi;
        semesterIdInput.value = id;
        namasemesterInput.value = nama; // กำหนดค่าให้
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
