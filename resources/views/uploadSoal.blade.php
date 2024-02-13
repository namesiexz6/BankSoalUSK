@extends('navbar')

@section('body')

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <div class="container">
                <h3 class="card-title">Prosedur Upload Soal :</h3>
                <hr>
                <ol>
                    <li>Upload file soal dengan format excel (.pdf)</li>
                    <li>Format file wajib *.pdf*</li>
                    <li>PASTIKAN TIDAK ADA IMPORT FILE LEBIH DARI 1 KALI, KARENA AKAN MENYEBABKAN DUPLIKASI SOAL!!</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="card my-5">
        <div class="card-body">
            <div class="container">
                <h3 class="card-title">Upload Soal</h3>
                <hr>
                <form action="{{ route('/uploadSoal')}}" method="post" enctype="multipart/form-data">

                    <label for="formFile" class="form-label">Pilih Semester:</label>
                    <select class="form-select" aria-label="Default select" name="semester" id="semester"
                        onchange="this.form.submit()">
                        <option hidden disabled selected value="{{ session.get('semester') }}">Semester
                            {{session.get('semester')}}</option>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                        <option value="3">Semester 3</option>
                        <option value="4">Semester 4</option>
                        <option value="5">Semester 5</option>
                        <option value="6">Semester 6</option>
                    </select>

                    <label for="mk" class="form-label mt-3">Pilih Mata Kuliah:</label>
                    <select class="form-select" aria-label="Default select" name="mk" id="mk">
                        @each (matakuliahs in matakuliah)
                        @if (matakuliahs.id_semester == session.get('semester'))
                        <option value="{{ matakuliahs.id }}">{{ matakuliahs.nama }}</option>
                        @endif
                        @end
                    </select>

                    <label for="namasoal" class="form-label mt-3">Nama Soal:</label>
                    <input class="form-control" name="namasoal" id="namasoal" type="text" placeholder="ex : Latihan 1"
                        aria-label="default input">

                    <label for="formFile" class="form-label  mt-3">Pilih File:</label>
                    <input class="form-control" type="file" name="formFile" id="formFile">

                    <button type="submit" class="btn btn-info text-light mt-3">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection