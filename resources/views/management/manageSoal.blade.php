@extends('management/management')
@section('content')

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.1.1/tinymce.min.js" integrity="sha512-bAtLCmEwg+N9nr6iVELr/SlDxBlyoF0iVdPxAvcOCfUiyi6RcuS6Lzawi78iPbAfbNyIUftvwK9HPWd+3p975Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
  <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;">Manajemen Soal</h2>
</div>

    <div class="w3-container">
        <div class="container">
            <form id="matakuliahForm">
                @csrf
                <div class="row">

                    <div class="col-md-3">
                        <label for="jenjang" class="form-label mt-3">Pilih Jenjang:</label>
                        <select class="form-select" aria-label="Default select" name="jenjang" id="jenjang">
                            <option value="">-- Pilih Jenjang --</option>
                            @foreach ($jenjang as $jj)
                            <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>{{ $jj->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="fakultas" class="form-label mt-3">Pilih Fakultas:</label>
                        <select class="form-select" aria-label="Default select" name="fakultas" id="fakultas" {{ !session('jenjang') ? 'disabled' : '' }}>
                            <option value="">-- Pilih Fakultas --</option>
                            @foreach ($fakultas as $f)
                            <option value="{{ $f->id }}" {{ session('fakultas') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="prodi" class="form-label mt-3">Pilih Prodi:</label>
                        <select class="form-select" aria-label="Default select" name="prodi" id="prodi" {{ !session('fakultas') ? 'disabled' : '' }}>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach ($prodi as $p)
                            <option value="{{ $p->id }}" {{ session('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="semester" class="form-label mt-3">Pilih Semester:</label>
                        <select class="form-select" aria-label="Default select" name="semester" id="semester" {{ !session('prodi') ? 'disabled' : '' }}>
                            <option value="">-- Pilih Semester --</option>
                            @foreach ($semester as $s)
                            <option value="{{ $s->id }}" {{ session('semester') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="matakuliah" class="form-label mt-3">Pilih Matakuliah:</label>
                        <select class="form-select" aria-label="Default select" name="matakuliah" id="matakuliah" {{ !session('prodi') ? 'disabled' : '' }}>
                            <option disabled selected value="">-- Pilih Matakuliah --</option>
                            @foreach ($matakuliah as $m)
                            <option value="{{ $m->id }}" {{ session('matakuliah') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div style="margin-left:10px; margin-right:10px;">
        <div id="soalContainer">
            <!-- เนื้อหาของ soal จะถูกอัพเดตที่นี่ -->
        </div>

        <button class="buttonadd mt-3" type="button" onclick="window.location.href='/addSoal'">Tambah soal</button>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        let jenjang = $('#jenjang');
        let fakultas = $('#fakultas');
        let prodi = $('#prodi');
        let semester = $('#semester');
        let matakuliah = $('#matakuliah');
        let matakuliahForm = $('#matakuliahForm');
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
                        fakultas.empty().append('<option value="">-- Pilih Fakultas --</option>');
                        $.each(result.fakultas, function(key, value) {
                            fakultas.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                        prodi.prop('disabled', true);
                        semester.prop('disabled', true);
                        matakuliah.prop('disabled', true);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            } else {
                fakultas.prop('disabled', true);
                prodi.prop('disabled', true);
                semester.prop('disabled', true);
                matakuliah.prop('disabled', true);
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
                        prodi.empty().append('<option value="">-- Pilih Prodi --</option>');
                        $.each(result.prodi, function(key, value) {
                            prodi.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                        semester.prop('disabled', true);
                        matakuliah.prop('disabled', true);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            } else {
                prodi.prop('disabled', true);
                semester.prop('disabled', true);
                matakuliah.prop('disabled', true);
            }
        });

        prodi.change(function() {
            let idProdi = this.value;
            if (idProdi) {
                semester.prop('disabled', false);
                $.ajax({
                    url: "{{ route('cariSemesterM2') }}",
                    type: "POST",
                    data: {
                        id_prodi: idProdi,
                        _token: csrfToken
                    },
                    dataType: 'json',
                    success: function(result) {
                        semester.empty().append('<option value="">-- Pilih Semester --</option>');
                        $.each(result.semester, function(key, value) {
                            semester.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                        matakuliah.prop('disabled', true);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            } else {
                semester.prop('disabled', true);
                matakuliah.prop('disabled', true);
            }
        });

        semester.change(function() {
            let idSemester = this.value;
            if (idSemester) {
                matakuliah.prop('disabled', false);
                $.ajax({
                    url: "{{ route('cariMatakuliahM2') }}",
                    type: "POST",
                    data: {
                        id_semester: idSemester,
                        _token: csrfToken
                    },
                    dataType: 'json',
                    success: function(result) {
                        matakuliah.empty().append('<option value="">-- Pilih Matakuliah --</option>');
                        $.each(result.matakuliah, function(key, value) {
                            matakuliah.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            } else {
                matakuliah.prop('disabled', true);
            }
        });

        matakuliah.change(function() {
            let idMatakuliah = this.value;
            if (idMatakuliah) {
                $.ajax({
                    url: "{{ route('cariMatakuliahM') }}",
                    type: "POST",
                    data: {
                        matakuliah: idMatakuliah,
                        _token: csrfToken
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#soalContainer').html(result.html);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }
        });
    });
</script>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this item?');
    }
</script>

@endsection
