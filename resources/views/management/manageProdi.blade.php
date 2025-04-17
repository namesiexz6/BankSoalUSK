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
  <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;">{{ __('management.manajemen_prodi') }}</h2>
</div>

    <div class="w3-container">
        <div class="container">
            <form id="fakultasForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="jenjang" class="form-label mt-3">{{ __('management.pilih_jenjang') }}:</label>
                        <select class="form-select" aria-label="Default select" name="jenjang" id="jenjang">
                            <option value="">-- {{ __('management.pilih_jenjang') }} --</option>
                            @foreach ($jenjang as $jj)
                                <option value="{{ $jj->id }}" {{ session('jenjang') == $jj->id ? 'selected' : '' }}>
                                    {{ $jj->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fakultas" class="form-label mt-3">{{ __('management.pilih_fakultas') }}:</label>
                        <select class="form-select" aria-label="Default select" name="fakultas" id="fakultas" {{ !session('jenjang') ? 'disabled' : '' }}>
                            <option disabled selected value="">-- {{ __('management.pilih_fakultas') }} --</option>
                            @foreach ($fakultas as $f)
                                <option value="{{ $f->id }}" {{ session('fakultas') == $f->id ? 'selected' : '' }}>
                                    {{ $f->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div style="margin-left:10px; margin-right:10px;">
        <div id="prodiContainer">
            <!-- เนื้อหาของ prodi จะถูกอัพเดตที่นี่ -->
        </div>

        <button class="buttonadd mt-3" type="button" id="adressBTN" onclick="openRegisterForm()">{{ __('management.tambah_prodi') }}</button>
    </div>

    <div id="registerForm" class="register-form">
        <form action="{{ route('tambahProdiM') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h1>{{ __('management.tambah_prodi') }}</h1>
            <label class="form-label mt-3">{{ __('management.nama_prodi') }}:</label>
            <input class="form-control" name="nama_prodi" id="nama_prodi" type="text" placeholder="ex: Informatika" required>
            <label class="form-label mt-3">{{ __('management.pilih_alamat') }}:</label><br>
            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang2">
                <option value="">-- {{ __('management.pilih_jenjang') }} --</option>
                @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="fakultas2" id="fakultas2" required>
                <option value="">-- {{ __('management.pilih_fakultas') }} --</option>
                @foreach ($fakultas as $f)
                    <option value="{{ $f->id }}">{{ $f->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="registerbtn">{{ __('management.submit') }}</button>
            <button class="buttoncancel mt-3" type="button" id="adressBTN" onclick="closeRegisterForm()">{{ __('management.batal') }}</button>
        </form>
    </div>

    <div id="registerFormEdit" class="register-form">
        <form action="{{ route('prodiM') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="edit" value="1">
            <h1>{{ __('management.edit') }} {{ __('management.nama_prodi') }}</h1>
            <label class="form-label mt-3">{{ __('management.nama_prodi') }}:</label>
            <input type="hidden" name="prodi_id" id="prodi_id" value="">
            <input class="form-control" name="nama_prodi" id="nama_prodi" type="text" value="" required>
            <label class="form-label mt-3">{{ __('management.pilih_alamat') }}:</label><br>
            <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang3">
                <option value="">-- {{ __('management.pilih_jenjang') }} --</option>
                @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                @endforeach
            </select>
            <select class="form-control" aria-label="Default select" name="fakultas2" id="fakultas3" required>
                <option value="">-- {{ __('management.pilih_fakultas') }} --</option>
                @foreach ($fakultas as $f)
                    <option value="{{ $f->id }}">{{ $f->nama }}</option>
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
        let fakultasForm = $('#fakultasForm');
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
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            } else {
                fakultas.prop('disabled', true);
            }
        });

        fakultas.change(function() {
            let idFakultas = this.value;
            if (idFakultas) {
                $.ajax({
                    url: "{{ route('cariFakultasM') }}",
                    type: "POST",
                    data: {
                        fakultas: idFakultas,
                        _token: csrfToken
                    },
                    success: function(result) {
                        $('#prodiContainer').html(result.html);
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

    function openRegisterFormEdit(id, nama, id_fakultas) {
        var form = document.getElementById("registerFormEdit");
        var prodiIdInput = form.querySelector("input[name='prodi_id']");
        var namaprodiInput = form.querySelector("input[name='nama_prodi']");
        var fakultasInput = form.querySelector("select[name='fakultas2']");
        fakultasInput.value = id_fakultas;
        prodiIdInput.value = id;
        namaprodiInput.value = nama; // กำหนดค่าให้
        form.style.display = "block";
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
        $('#jenjang2').on('change', function() {
            var idCountry = this.value;
            $("#fakultas2").html('');
            $.ajax({
                url: "{{ route('cariFakultasM2') }}",
                type: "POST",
                data: {
                    id_jenjang: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#fakultas2').html('<option value="">-- {{ __('management.pilih_fakultas') }} --</option>');
                    console.log(result);
                    $.each(result.fakultas, function(key, value) {
                        $("#fakultas2").append('<option value="' + value.id + '">' + value.nama + '</option>');
                    });
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#jenjang3').on('change', function() {
            var idCountry = this.value;
            $("#fakultas3").html('');
            $.ajax({
                url: "{{ route('cariFakultasM2') }}",
                type: "POST",
                data: {
                    id_jenjang: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#fakultas3').html('<option value="">-- {{ __('management.pilih_fakultas') }} --</option>');
                    console.log(result);
                    $.each(result.fakultas, function(key, value) {
                        $("#fakultas3").append('<option value="' + value.id + '">' + value.nama + '</option>');
                    });
                }
            });
        });
    });
</script>
@endsection
