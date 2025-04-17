@extends('navbar')
@section('body')
<div class="background"
    style="background-image: url('{{  asset('background.png') }}'); background-size: cover; background-position: center; height: 32vh;">
    <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;" id="judul">
        {{ __('soal.judul') }}
    </h2>
    <div class="container mt-3">

        <div class="row">
            <div class="col-md-3">
                <form id="semesterForm">
                    @csrf
                    <label for="jenjang" class="form-label mt-3" style="color: white;" data-translate="soal.jenjang">{{ __('soal.jenjang') }}:</label>
                    <select class="form-control" aria-label="Default select" name="jenjang" id="jenjang">
                        <option value="">{{ __('soal.pilih_jenjang') }}</option>
                        @foreach ($jenjang as $jj)
                        <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>
                            {{ $jj->nama }}
                        </option>
                        @endforeach
                    </select>
            </div>
            <div class="col-md-3">
                <label for="fakultas" class="form-label mt-3" style="color: white;" data-translate="soal.fakultas">{{ __('soal.fakultas') }}:</label>
                <select class="form-control" aria-label="Default select" name="fakultas" id="fakultas"
                    {{ !session('jenjang') ? 'disabled' : '' }}>
                    <option value="">{{ __('soal.pilih_fakultas') }}</option>
                    @foreach ($fakultas as $f)
                    <option value="{{ $f->id }}" {{ session('fakultas') == $f->id ? 'selected' : '' }}>{{ $f->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="prodi" class="form-label mt-3" style="color: white;" data-translate="soal.prodi">{{ __('soal.prodi') }}:</label>
                <select class="form-control" aria-label="Default select" name="prodi" id="prodi"
                    {{ !session('fakultas') ? 'disabled' : '' }}>
                    <option value="">{{ __('soal.pilih_prodi') }}</option>
                    @foreach ($prodi as $p)
                    <option value="{{ $p->id }}" {{ session('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="semester" class="form-label mt-3" style="color: white;" data-translate="soal.semester">{{ __('soal.semester') }}:</label>
                <select class="form-control" aria-label="Default select" name="semester" id="semester"
                    {{ !session('prodi') ? 'disabled' : '' }}>
                    <option value="">{{ __('soal.pilih_semester') }}</option>
                    @foreach ($semester as $s)
                    <option value="{{ $s->id }}" {{ session('semester') == $s->id ? 'selected' : '' }}>{{ $s->nama }}
                    </option>
                    @endforeach
                </select>
                </form>
            </div>
        </div>

    </div>
</div>
<div class="container ">
    <div id="mataKuliahContainer">
        <!-- ตำแหน่งที่จะอัพเดตเนื้อหา HTML -->
    </div>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    let jenjang = $('#jenjang');
    let fakultas = $('#fakultas');
    let prodi = $('#prodi');
    let semester = $('#semester');
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
                    fakultas.empty().append('<option value="">{{ __('soal.pilih_fakultas') }}</option>');
                    $.each(result.fakultas, function(key, value) {
                        fakultas.append('<option value="' + value.id + '">' + value
                            .nama + '</option>');
                    });
                    prodi.prop('disabled', true);
                    semester.prop('disabled', true);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        } else {
            fakultas.prop('disabled', true);
            prodi.prop('disabled', true);
            semester.prop('disabled', true);
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
                    prodi.empty().append('<option value="">{{ __('soal.pilih_prodi') }}</option>');
                    $.each(result.prodi, function(key, value) {
                        prodi.append('<option value="' + value.id + '">' + value
                            .nama + '</option>');
                    });
                    semester.prop('disabled', true);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        } else {
            prodi.prop('disabled', true);
            semester.prop('disabled', true);
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
                    semester.empty().append('<option value="">{{ __('soal.pilih_semester') }}</option>');
                    $.each(result.semester, function(key, value) {
                        semester.append('<option value="' + value.id + '">' + value
                            .nama + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        } else {
            semester.prop('disabled', true);
        }
    });

    semester.change(function() {
        let idSemester = this.value;
        if (idSemester) {
            $.ajax({
                url: "{{ route('cariSemester') }}",
                type: "POST",
                data: {
                    semester: idSemester,
                    _token: csrfToken
                },
                dataType: 'json',
                success: function(result) {
                    // อัพเดตเนื้อหาของหน้าเว็บตามผลลัพธ์ที่ได้รับ
                    $('#mataKuliahContainer').html(result.html);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        } else {
            $('#mataKuliahContainer').empty();
        }
    });
});
</script>

@endsection
