@extends('management/management')
@section('content')

<head>

    <link rel="stylesheet" href="/css/popupform.css">
</head>
<div style="margin-left:15%">

    <div class="w3-container w3-light-blue">
        <h1>Manage Semester</h1>
    </div>

    <div class="w3-container">
        <div class="container">
            <form action="{{ route('cariProdiM') }}" method="post" id="prodiForm">
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
                </div>
            </form>
        </div>
    </div>
    <div style="margin-left:10px; margin-right:10px;">
        @if(session('prodi')!= 0)

        <h2 class="mt-5">Daftar Soal</h2>
        <table class="table table-bordered table-light table-striped my-3">
            <thead class="table-dark">
                <input type="hidden" name="id_prodi" value="1">
                <tr>
                    <th colspan="5">Prodi {{session('Prodi_nama')}}</th>
                </tr>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Semester</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>


                @foreach ($semester as $s)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $s->nama }}</td>
                    <td>
                        <div class="d-flex">

                            <button type="submit" name="edit" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;" onclick="openRegisterFormEdit('{{ $s->id }}','{{ $s->nama }}','{{ $s->id_prodi }}')">Edit</button>
                            <form action="{{ route('semesterM')}}" method="post">
                                @csrf
                                <input type="hidden" name="semester_id" value="{{ $s->id }}">
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach



            </tbody>
        </table>
        @endif

        <button class="buttonadd mt-3" type="button" id="adressBTN" onclick="openRegisterForm()">Tambah Semester</button>
    </div>

    <div id="registerForm" class="register-form">

        <form action="{{ route('tambahSemesterM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Tambah Semester</h1>
            <label class="form-label mt-3">Nama Semester:</label>
            <input class="form-control" name="nama_semester" id="nama_semester" type="text" placeholder="ex: Semester 1" required>

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
            <select class="form-control" aria-label="Default select" name="prodi2" id="prodi2" required>
                <option value="">-- Pilih Prodi --</option>
                @foreach ($prodi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="registerbtn">Submit</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterForm()">Batal</button>
        </form>
    </div>

    <div id="registerFormEdit" class="register-form">

        <form action="{{ route('semesterM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="edit" value="1">
            <h1>Edit Semester</h1>
            <label class="form-label mt-3">Nama Semester:</label>
            <input type="hidden" name="semester_id" id="semester_id" value="">
            <input class="form-control" name="nama_semester" id="nama_semester" type="text" value="" required>

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
            <select class="form-control" aria-label="Default select" name="prodi2" id="prodi3" required>
                <option value="">-- Pilih Prodi --</option>
                @foreach ($prodi as $p)
                <option value="{{ $p->id }}">{{ $p->nama }}</option>
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
        let fakultas = $('#fakultas');
        let prodi = $('#prodi');
        let prodiForm = $('#prodiForm');
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
                        prodi.empty().append('<option value="">-- Pilih Prodi --</option>');
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
            prodiForm.submit();
        });
    });
</script>
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

    function openRegisterFormEdit(id, nama, id_prodi) {
        var form = document.getElementById("registerFormEdit");
        var semesterIdInput = form.querySelector("input[name='semester_id'");
        var namasemesterInput = form.querySelector("input[name='nama_semester'");
        var prodiInput = form.querySelector("select[name='prodi2'");
        prodiInput.value = id_prodi;
        semesterIdInput.value = id;
        namasemesterInput.value = nama; // กำหนดค่าให้
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

                }
            });
        });
    });
</script>
@endsection