@extends('management/management')
@section('content')

<head>

    <link rel="stylesheet" href="/css/popupform.css">
</head>

<div style="margin-left:15%">
    <div class="w3-container w3-light-blue">
        <h1>Manage Soal</h1>
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
                <div class="col-md-3">
                    <form action="{{ route('cariProdiM') }}" method="post" enctype="multipart/form-data">
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
                    <form action="{{ route('cariSemesterM') }}" method="post" enctype="multipart/form-data">
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
                <div class="col-md-3">
                    <form action="{{ route('cariMatakuliahM') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <label for="matakuliah" class="form-label mt-3">Pilih Matakuliah:</label>
                        @if(session('id_semester') )
                        <select class="form-select" aria-label="Default select" name="matakuliah" id="matakuliah" onchange="this.form.submit()">
                            <option hidden disabled selected value="{{ session('id_matakuliah') }}"> {{session('matakuliah')}}</option>
                            @foreach ($matakuliah as $m)
                            @if($m->id_semester == session('id_semester'))
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                            @endif
                            @endforeach
                        </select>
                        @else
                        <select class="form-select" aria-label="Default select" name="matakuliah" id="matakuliah" disabled>
                            <option selected>---</option>
                        </select>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div style="margin-left:10px; margin-right:10px;">
        @if(session('id_matakuliah')!= 0)

        <h2 class="mt-5">Daftar Soal</h2>
        <table class="table table-bordered table-light table-striped my-3">
            <thead class="table-dark">
                <input type="hidden" name="id_semester" value="1">
                <tr>
                    <th colspan="5">Mata Kuliah {{session('matakuliah')}}</th>
                </tr>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Soal</th>
                    <th scope="col">Dibuat Oleh</th>
                    <th scope="col">Update</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>


                @foreach ($soal as $soals)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $soals->nama_soal }}</td>
                    <td>{{ $soals->nama }}</td>
                    <td>{{ $soals->updated_at }}</td>
                    <td>
                        <div class="d-flex">

                            <button type="submit" name="edit" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;" onclick="openRegisterFormEdit('{{ $soals->id }}','{{ $soals->nama_soal }}','{{ $soals->id_matakuliah }}')">Edit</button>
                            <form action="{{ route('soalM')}}" method="post">
                                @csrf
                                <input type="hidden" name="soal_id" value="{{ $soals->id }}">
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </form>
                        </div>
                    <td>
                </tr>
                @endforeach



            </tbody>
        </table>



        @endif

        <button class="buttonadd mt-3" type="button" id="adressBTN" onclick="openRegisterForm()">Tambah Soal</button>
    </div>

    <div id="registerForm" class="register-form">

        <form action="{{ route('tambahSoalM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Tambah Mata kuliah</h1>
            <label class="form-label mt-3">Nama Soal:</label>
            <input class="form-control" name="nama_soal" id="nama_soal" type="text" placeholder="ex : Latihan 1" required>

            <label for="formFile" class="form-label  mt-3">Pilih File:</label>
            <input class="form-control" type="file" name="formFile" id="formFile" required>
            <label for="jenjang2" class="form-label  mt-3">Pilih alamat:</label> <br>
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
            <select class="form-control" aria-label="Default select" name="prodi2" id="prodi2">
                <option value="">-- Pilih Prodi --</option>
                @foreach ($prodi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="semester2" id="semester2">
                <option value="">-- Pilih Semester --</option>
                @foreach ($semester as $s)
                <option value="{{ $s->id }}">{{ $s->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="matakuliah2" id="matakuliah2" required>
                <option value="">-- Pilih Matakuliah --</option>
                @foreach ($matakuliah as $m)
                <option value="{{ $m->id }}">{{ $m->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="registerbtn">Submit</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterForm()">Batal</button>
        </form>
    </div>
    <div id="registerFormEdit" class="register-form">

        <form action="{{ route('editSoalM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Edit Soal</h1>
            <label class="form-label mt-3">Nama Soal:</label>
            <input type="hidden" name="soal_id" id="soal_id" value="">
            <input class="form-control" name="nama_soal" id="nama_soal" type="text" value="" required>
            <label for="formFile" class="form-label  mt-3">Pilih File:</label>
            <input class="form-control" type="file" name="formFile" id="formFile" required>
            <label for="jenjang2" class="form-label  mt-3">Pilih alamat:</label> <br>
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
            <select class="form-control" aria-label="Default select" name="prodi2" id="prodi3">
                <option value="">-- Pilih Prodi --</option>
                @foreach ($prodi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="semester2" id="semester3">
                <option value="">-- Pilih Semester --</option>
                @foreach ($semester as $s)
                <option value="{{ $s->id }}">{{ $s->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="matakuliah2" id="matakuliah3" required>
                <option value="">-- Pilih Matakuliah --</option>
                @foreach ($matakuliah as $m)
                <option value="{{ $m->id }}">{{ $m->nama }}</option>
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

    function openRegisterFormEdit(id, nama, id_matakuliah) {
        var form = document.getElementById("registerFormEdit");
        var soalIdInput = form.querySelector("input[name='soal_id'");
        var namasoalInput = form.querySelector("input[name='nama_soal'");
        var matakuliahInput = form.querySelector("select[name='matakuliah2'");
        matakuliahInput.value = id_matakuliah;
        soalIdInput.value = id;
        namasoalInput.value = nama; // กำหนดค่าให้
        form.style.display = "block";;
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
        let jenjang = document.getElementById("jenjang2");
        let fakultas = document.getElementById("fakultas2");
        let prodi = document.getElementById("prodi2");
        let semester = document.getElementById("semester2");
        let matakuliah = document.getElementById("matakuliah2");

        jenjang.addEventListener('change', function() {
            var idCountry = this.value;
            fakultas.innerHTML = '';
            $.ajax({
                url: "{{ route ('cariFakultasM2')}}",
                type: "POST",
                data: {
                    id_jenjang: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    fakultas.innerHTML = '<option value="">-- Pilih Fakultas --</option>';
                    console.log(result);
                    $.each(result.fakultas, function(key, value) {
                        fakultas.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                    });
                    prodi.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                }
            });
        });

        fakultas.addEventListener('change', function() {
            var idCountry = this.value;
            prodi.innerHTML = '';
            $.ajax({
                url: "{{ route ('cariProdiM2')}}",
                type: "POST",
                data: {
                    id_fakultas: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    prodi.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                    console.log(result);
                    $.each(result.prodi, function(key, value) {
                        prodi.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                    });
                    semester.innerHTML = '<option value="">-- Pilih Semester --</option>';
                }
            });
        });

        prodi.addEventListener('change', function() {
            var idCountry = this.value;
            semester.innerHTML = '';
            $.ajax({
                url: "{{ route ('cariSemesterM2')}}",
                type: "POST",
                data: {
                    id_prodi: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    semester.innerHTML = '<option value="">-- Pilih Semester --</option>';
                    console.log(result);
                    $.each(result.semester, function(key, value) {
                        semester.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                    });
                    matakuliah.innerHTML = '<option value="">-- Pilih Matakuliah --</option>';
                }
            });
        });
        semester.addEventListener('change', function() {
            var idCountry = this.value;
            matakuliah.innerHTML = '';
            $.ajax({
                url: "{{ route ('cariMatakuliahM2')}}",
                type: "POST",
                data: {
                    id_semester: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    matakuliah.innerHTML = '<option value="">-- Pilih Matakuliah --</option>';
                    console.log(result);
                    $.each(result.matakuliah, function(key, value) {
                        matakuliah.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                    });
                }
            });
        });
       
        
});

</script>
<script>
    $(document).ready(function() {
        let jenjang = document.getElementById("jenjang3");
        let fakultas = document.getElementById("fakultas3");
        let prodi = document.getElementById("prodi3");
        let semester = document.getElementById("semester3");
        let matakuliah = document.getElementById("matakuliah3");

        jenjang.addEventListener('change', function() {
            var idCountry = this.value;
            fakultas.innerHTML = '';
            $.ajax({
                url: "{{ route ('cariFakultasM2')}}",
                type: "POST",
                data: {
                    id_jenjang: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    fakultas.innerHTML = '<option value="">-- Pilih Fakultas --</option>';
                    console.log(result);
                    $.each(result.fakultas, function(key, value) {
                        fakultas.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                    });
                    prodi.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                }
            });
        });

        fakultas.addEventListener('change', function() {
            var idCountry = this.value;
            prodi.innerHTML = '';
            $.ajax({
                url: "{{ route ('cariProdiM2')}}",
                type: "POST",
                data: {
                    id_fakultas: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    prodi.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                    console.log(result);
                    $.each(result.prodi, function(key, value) {
                        prodi.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                    });
                    semester.innerHTML = '<option value="">-- Pilih Semester --</option>';
                }
            });
        });

        prodi.addEventListener('change', function() {
            var idCountry = this.value;
            semester.innerHTML = '';
            $.ajax({
                url: "{{ route ('cariSemesterM2')}}",
                type: "POST",
                data: {
                    id_prodi: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    semester.innerHTML = '<option value="">-- Pilih Semester --</option>';
                    console.log(result);
                    $.each(result.semester, function(key, value) {
                        semester.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                    });
                    matakuliah.innerHTML = '<option value="">-- Pilih Matakuliah --</option>';
                }
            });
        });
        semester.addEventListener('change', function() {
            var idCountry = this.value;
            matakuliah.innerHTML = '';
            $.ajax({
                url: "{{ route ('cariMatakuliahM2')}}",
                type: "POST",
                data: {
                    id_semester: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    matakuliah.innerHTML = '<option value="">-- Pilih Matakuliah --</option>';
                    console.log(result);
                    $.each(result.matakuliah, function(key, value) {
                        matakuliah.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                    });
                }
            });
        });
       
        
});

</script>
@endsection