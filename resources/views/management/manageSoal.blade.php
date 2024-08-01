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
    <div class="w3-container w3-light-blue">
        <h1>Manajemen Soal</h1>
    </div>

    <div class="w3-container">
        <div class="container">
            <form action="{{ route('cariMatakuliahM') }}" method="post" id="matakuliahForm">
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
        @if(session('matakuliah')!= 0)

        <h2 class="mt-5">Daftar Soal</h2>
        <table class="table table-bordered table-light table-striped my-3">
            <thead class="table-dark">
                <input type="hidden" name="id_semester" value="1">
                <tr>
                    <th colspan="5">Mata Kuliah {{session('matakuliah_nama')}}</th>
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
                            <form action="{{ route('soalM')}}" method="post" onsubmit="return confirmDelete()">
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
        <button class="buttonadd mt-3" type="button" onclick="window.location.href='/addSoal'">Tambah soal</button>


    <!-- <button class="buttonadd mt-3" type="button" id="adressBTN" onclick="openRegisterForm()">Tambah Soal</button>-->
    </div>

    <div id="registerForm" class="register-form">

        <form action="{{ route('tambahSoalM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <h1>Tambah Mata kuliah</h1>
            <label class="form-label mt-3">Nama Soal:</label>
            <input class="form-control" name="nama_soal" id="nama_soal" type="text" placeholder="ex : Latihan 1" required>

            <div id="fileOptions" class="tab">
                <button class="tablinks active" onclick="toggleFileOption(event, 'upload')">Upload File</button>
                <button class="tablinks" onclick="toggleFileOption(event, 'textarea')">Textarea</button>
            </div>

            <!-- input file และ textarea -->
            <div id="upload" class="tabcontent" style="display: block;">
                <label class="form-label mt-3">Pilih File:</label>
                <input class="form-control" type="file" name="formFile" id="formFile">
            </div>
            <div id="textarea" class="tabcontent" style="display: none;">
                <textarea class="form-control" id="textareaContent" name="textareaContent"></textarea>
            </div>
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

        <form action="{{ route('soalM') }}" method="post" enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="edit" value="1">
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
            matakuliahForm.submit();
        });
    });
</script>
<script>
    function toggleFileOption(evt, optionName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(optionName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>
<script>
    function openRegisterForm() {
        document.getElementById("registerForm").style.display = "block";

    }

    function closeRegisterForm() {
        document.getElementById("registerForm").style.display = "none";
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

    function confirmDelete() {
        return confirm('Are you sure you want to delete this item?');
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

   <script>
        tinymce.init({
            selector: '#textareaContent',
            promotion: false,
            plugins: 'preview importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
            editimage_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | print | pagebreak anchor codesample | ltr rtl",
            image_advtab: true,
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'file' || meta.filetype === 'media') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', '*');
                    input.onchange = function() {
                        var file = this.files[0];
                        callback(URL.createObjectURL(file), {
                            text: file.name
                        });
                    };
                    input.click();
                } else if (meta.filetype === 'image') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function() {
                        var file = this.files[0];
                        callback(URL.createObjectURL(file), {
                            alt: file.name
                        });
                    };
                    input.click();
                }
            },
            height: '297mm',
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image table',
            skin: 'oxide',
            content_css: 'default',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });
    </script>
@endsection
