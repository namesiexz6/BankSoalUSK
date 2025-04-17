@extends('management/management') 
@section('content')

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
  style="background-image: url('{{  asset('background.png')}}'); background-size: cover; background-position: top; height: 10vh; display: flex; align-items: center; justify-content: center;">
  <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;">{{ __('management.manajemen_matakuliah') }}</h2>
</div>
    <div class="w3-container">
        <div class="container">
            <form id="semesterForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="jenjang" class="form-label mt-3">{{ __('management.pilih_jenjang') }}:</label>
                        <select class="form-select" aria-label="Default select" name="jenjang" id="jenjang">
                            <option value="">-- {{ __('management.pilih_jenjang') }} --</option>
                            @foreach ($jenjang as $jj)
                            <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>{{ $jj->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fakultas" class="form-label mt-3">{{ __('management.pilih_fakultas') }}:</label>
                        <select class="form-select" aria-label="Default select" name="fakultas" id="fakultas" {{ !session('jenjang') ? 'disabled' : '' }}>
                            <option value="">-- {{ __('management.pilih_fakultas') }} --</option>
                            @foreach ($fakultas as $f)
                            <option value="{{ $f->id }}" {{ session('fakultas') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="prodi" class="form-label mt-3">{{ __('management.pilih_prodi') }}:</label>
                        <select class="form-select" aria-label="Default select" name="prodi" id="prodi" {{ !session('fakultas') ? 'disabled' : '' }}>
                            <option value="">-- {{ __('management.pilih_prodi') }} --</option>
                            @foreach ($prodi as $p)
                            <option value="{{ $p->id }}" {{ session('prodi') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="semester" class="form-label mt-3">{{ __('management.pilih_semester') }}:</label>
                        <select class="form-select" aria-label="Default select" name="semester" id="semester" {{ !session('prodi') ? 'disabled' : '' }}>
                            <option disabled selected value="">-- {{ __('management.pilih_semester') }} --</option>
                            @foreach ($semester as $s)
                            <option value="{{ $s->id }}" {{ session('semester') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div style="margin-left:10px; margin-right:10px;">
        <div id="matakuliahContainer">
            <!-- เนื้อหาของ matakuliah จะถูกอัพเดตที่นี่ -->
        </div>

        <button class="buttonadd" type="button" id="adressBTN" onclick="openRegisterForm()">{{ __('management.tambah_matakuliah') }}</button>
    </div>

    <div id="registerForm" class="register-form">
        <form action="{{ route('tambahMatakuliahM') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h1>{{ __('management.tambah_matakuliah') }}</h1>
            <label class="form-label mt-3">{{ __('management.kode_matakuliah') }}:</label>
            <input type="text" name="kode" placeholder="ex: BM205" style="width: 100%;" required>
            <label class="form-label mt-3">{{ __('management.nama_matakuliah') }}:</label>
            <input type="text" name="nama" placeholder="ex: Biologi" style="width: 100%;" required>
            <label class="form-label mt-3">{{ __('management.sks') }}:</label><br>
            <input type="text" name="sks" placeholder="ex: 3" style="width: 30%;" required><br>
            <label class="form-label mt-3">{{ __('management.pilih_alamat') }}:</label><br>
            <div id="addresses">
                <div class="address">
                    <select class="form-control jenjang2" name="jenjang2[]">
                        <option value="">-- {{ __('management.pilih_jenjang') }} --</option>
                        @foreach ($jenjang as $jj)
                        <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                        @endforeach
                    </select>
                    <select class="form-control fakultas2" name="fakultas2[]">
                        <option value="">-- {{ __('management.pilih_fakultas') }} --</option>
                    </select>
                    <select class="form-control prodi2" name="prodi2[]">
                        <option value="">-- {{ __('management.pilih_prodi') }} --</option>
                    </select>
                    <select class="form-control semester2" name="semester2[]" required>
                        <option value="">-- {{ __('management.pilih_semester') }} --</option>
                    </select>
                </div>
            </div>
            <button type="button" id="addAddressButton">{{ __('management.tambah_alamat') }}</button>
            <button type="submit" class="registerbtn">{{ __('management.submit') }}</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterForm()">{{ __('management.batal') }}</button>
        </form>
    </div>

    <div id="registerFormEdit" class="register-form">
        <form action="{{ route('matakuliahM') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h1>Edit {{ __('management.nama_matakuliah') }}</h1>
            <input type="hidden" name="edit" value="1">
            <input type="hidden" name="matakuliah_id" id="matakuliah_id" value="">
            <label class="form-label mt-3">{{ __('management.kode_matakuliah') }}:</label>
            <input type="text" name="kode" placeholder="ex: BM205" style="width: 100%;" required>
            <label class="form-label mt-3">{{ __('management.nama_matakuliah') }}:</label>
            <input type="text" name="nama" placeholder="ex: Biologi" style="width: 100%;" required>
            <label class="form-label mt-3">{{ __('management.sks') }}:</label><br>
            <input type="text" name="sks" placeholder="ex: 3" style="width: 30%;" required><br>
            <label class="form-label mt-3">{{ __('management.pilih_alamat') }}:</label><br>
            <div id="addresses2">
                <div class="address">
                    <select class="form-control jenjang2" name="jenjang2[]">
                        <option value="">-- {{ __('management.pilih_jenjang') }} --</option>
                        @foreach ($jenjang as $jj)
                        <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                        @endforeach
                    </select>
                    <select class="form-control fakultas2" name="fakultas2[]">
                        <option value="">-- {{ __('management.pilih_fakultas') }} --</option>
                    </select>
                    <select class="form-control prodi2" name="prodi2[]">
                        <option value="">-- {{ __('management.pilih_prodi') }} --</option>
                    </select>
                    <select class="form-control semester2" name="semester2[]" required>
                        <option value="">-- {{ __('management.pilih_semester') }} --</option>
                    </select>
                </div>
            </div>
            <button type="button" id="addAddressButton2">{{ __('management.tambah_alamat') }}</button>
            <button type="submit" class="registerbtn">{{ __('management.submit') }}</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterFormEdit()">{{ __('management.batal') }}</button>
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

    function openRegisterFormEdit(id, kode, nama, sks, id_semester) {
        var form = document.getElementById("registerFormEdit");
        var matakuliahIdInput = form.querySelector("input[name='matakuliah_id'");
        var kodematakuliahInput = form.querySelector("input[name='kode'");
        var namamatakuliahInput = form.querySelector("input[name='nama'");
        var sksmatakuliahInput = form.querySelector("input[name='sks'");
        var semesterInput = form.querySelector("select[name='semester2[]'");
        matakuliahIdInput.value = id;
        kodematakuliahInput.value = kode; // กำหนดค่าให้
        namamatakuliahInput.value = nama; // กำหนดค่าให้
        sksmatakuliahInput.value = sks; // กำหนดค่าให้
        semesterInput.value = id_semester;
        form.style.display = "block";
    }

    function closeRegisterFormEdit() {
        document.getElementById("registerFormEdit").style.display = "none";
    }

    function confirmDelete() {
        return confirm('Are you sure you want to delete this item?');
    }

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
                        fakultas.empty().append('<option value="">-- {{ __('management.pilih_fakultas') }} --</option>');
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
                        prodi.empty().append('<option value="">-- {{ __('management.pilih_prodi') }} --</option>');
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
                        semester.empty().append('<option value="">-- {{ __('management.pilih_semester') }} --</option>');
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
            let idSemester = this.value;
            if (idSemester) {
                $.ajax({
                    url: "{{ route('cariSemesterM') }}",
                    type: "POST",
                    data: {
                        semester: idSemester,
                        _token: csrfToken
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#matakuliahContainer').html(result.html);
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
    $(document).ready(function() {
        function attachListeners() {
            $('.jenjang2').off('change').on('change', function() {
                var idJenjang = this.value;
                var parent = $(this).closest('.address');
                var fakultasSelect = parent.find('.fakultas2');
                fakultasSelect.html('');
                $.ajax({
                    url: "{{ route('cariFakultasM2') }}",
                    type: "POST",
                    data: {
                        id_jenjang: idJenjang,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        fakultasSelect.html('<option value="">-- {{ __('management.pilih_fakultas') }} --</option>');
                        $.each(result.fakultas, function(key, value) {
                            fakultasSelect.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                        parent.find('.prodi2').html('<option value="">-- {{ __('management.pilih_prodi') }} --</option>');
                    }
                });
            });

            $('.fakultas2').off('change').on('change', function() {
                var idFakultas = this.value;
                var parent = $(this).closest('.address');
                var prodiSelect = parent.find('.prodi2');
                prodiSelect.html('');
                $.ajax({
                    url: "{{ route('cariProdiM2') }}",
                    type: "POST",
                    data: {
                        id_fakultas: idFakultas,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        prodiSelect.html('<option value="">-- {{ __('management.pilih_prodi') }} --</option>');
                        $.each(result.prodi, function(key, value) {
                            prodiSelect.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                        parent.find('.semester2').html('<option value="">-- {{ __('management.pilih_semester') }} --</option>');
                    }
                });
            });

            $('.prodi2').off('change').on('change', function() {
                var idProdi = this.value;
                var parent = $(this).closest('.address');
                var semesterSelect = parent.find('.semester2');
                semesterSelect.html('');
                $.ajax({
                    url: "{{ route('cariSemesterM2') }}",
                    type: "POST",
                    data: {
                        id_prodi: idProdi,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        semesterSelect.html('<option value="">-- {{ __('management.pilih_semester') }} --</option>');
                        $.each(result.semester, function(key, value) {
                            semesterSelect.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                    }
                });
            });
        }

        attachListeners();

        function addAddress() {
            var container = $('#addresses');
            var addressInput = `
            <div class="address">
                <select class="form-control jenjang2" name="jenjang2[]">
                    <option value="">-- {{ __('management.pilih_jenjang') }} --</option>
                    @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                    @endforeach
                </select>
                <select class="form-control fakultas2" name="fakultas2[]">
                    <option value="">-- {{ __('management.pilih_fakultas') }} --</option>
                </select>
                <select class="form-control prodi2" name="prodi2[]">
                    <option value="">-- {{ __('management.pilih_prodi') }} --</option>
                </select>
                <select class="form-control semester2" name="semester2[]">
                    <option value="">-- {{ __('management.pilih_semester') }} --</option>
                </select>
            </div>`;
            container.append(addressInput);
            attachListeners();
        }

        function addAddress2() {
            var container = $('#addresses2');
            var addressInput = `
            <div class="address">
                <select class="form-control jenjang2" name="jenjang2[]">
                    <option value="">-- {{ __('management.pilih_jenjang') }} --</option>
                    @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                    @endforeach
                </select>
                <select class="form-control fakultas2" name="fakultas2[]">
                    <option value="">-- {{ __('management.pilih_fakultas') }} --</option>
                </select>
                <select class="form-control prodi2" name="prodi2[]">
                    <option value="">-- {{ __('management.pilih_prodi') }} --</option>
                </select>
                <select class="form-control semester2" name="semester2[]">
                    <option value="">-- {{ __('management.pilih_semester') }} --</option>
                </select>
            </div>`;
            container.append(addressInput);
            attachListeners();
        }

        $('#addAddressButton').on('click', addAddress);
        $('#addAddressButton2').on('click', addAddress2);
    });
</script>
@endsection
