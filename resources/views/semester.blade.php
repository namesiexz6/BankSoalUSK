@extends('navbar')
@section('body')
<div class="container mt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
            <form action="{{ route('cariSemester') }}" method="post" id="semesterForm">
            @csrf
                <label for="jenjang" class="form-label mt-3">Pilih Jenjang:</label>
                <select class="form-control" aria-label="Default select" name="jenjang" id="jenjang">
    
                    <option value="">-- Pilih Jenjang --</option>
                    @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>{{ $jj->nama }}</option>
                    @endforeach
                </select>
            </div>   
            <div class="col-md-3">
                <label for="fakultas" class="form-label mt-3">Pilih Fakultas:</label>
                <select class="form-control" aria-label="Default select" name="fakultas" id="fakultas" {{ !session('jenjang') ? 'disabled' : '' }}>
                    <option value="">-- Pilih Fakultas --</option>
                    @foreach ($fakultas as $f)
                    <option value="{{ $f->id }}" {{ session('fakultas') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                    @endforeach
                </select>
             </div>    
             <div class="col-md-3">
                <label for="prodi" class="form-label mt-3">Pilih Prodi:</label>
                <select class="form-control" aria-label="Default select" name="prodi" id="prodi" {{ !session('fakultas') ? 'disabled' : '' }}>
                    <option value="">-- Pilih Prodi --</option>
                    @foreach ($prodi as $p)
                    <option value="{{ $p->id }}" {{ session('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                    @endforeach
                </select>
             </div>   
             <div class="col-md-3">
                    <label for="semester" class="form-label mt-3">Pilih Semester:</label>
                    <select class="form-control" aria-label="Default select" name="semester" id="semester" {{ !session('prodi') ? 'disabled' : '' }}>
                        <option disabled selected value="">-- Pilih Semester --</option>
                        @foreach ($semester as $s)
                        <option value="{{ $s->id }}" {{ session('semester') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    @if(session('semester') != 0)
    <h2 class="mt-5">Daftar Mata Kuliah</h2>
    <table class="table table-bordered table-light table-striped my-3">
        <thead class="table-dark">
            <tr>
                <th colspan="5">{{ session('semester_nama') }}</th>
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
                    <form action="{{ route('pilihsoal') }}" method="post">
                        @csrf
                        <input type="hidden" name="matakuliah_id" value="{{ $matakuliahs->id }}">
                        <button type="submit" class="btn btn-info text-light">Lihat</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        let jenjang = $('#jenjang');
        let fakultas = $('#fakultas');
        let prodi = $('#prodi');
        let semester = $('#semester');
        let semesterForm = $('#semesterForm');
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
                        prodi.empty().append('<option value="">-- Pilih Prodi --</option>');
                        $.each(result.prodi, function(key, value) {
                            prodi.append('<option value="' + value.id + '">' + value.nama + '</option>');
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
                        semester.empty().append('<option value="">-- Pilih Semester --</option>');
                        $.each(result.semester, function(key, value) {
                            semester.append('<option value="' + value.id + '">' + value.nama + '</option>');
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
            semesterForm.submit();
        });
    });
</script>
@endsection
