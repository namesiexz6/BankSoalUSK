@extends('navbar')
@section('body')
<div class="container mt-3">

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <form action="{{ route('cariJenjang') }}" method="post" enctype="multipart/form-data">
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
                <form action="{{ route('cariFakultas') }}" method="post" enctype="multipart/form-data">
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
                <form action="{{ route('cariProdi') }}" method="post" enctype="multipart/form-data">
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
                <form action="{{ route('cariSemester') }}" method="post" enctype="multipart/form-data">
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



    @if(session('id_semester')!= 0)
    <h2 class="mt-5">Daftar Mata Kuliah</h2>
    <table class="table table-bordered table-light table-striped my-3">
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
                <form action="{{ route('pilihsoal')}}" method="post">
                    @csrf
                    <input type="hidden" name="matakuliah_id" value="{{ $matakuliahs->id }}">
                    <td><button type="submit" class="btn btn-info text-light">Lihat</button></td>
                </form>
            </tr>
            @endforeach

    </table>
    @endif
</div>
@endsection