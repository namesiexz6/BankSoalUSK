@extends('management/management')
@section('content')

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/popupform.css">
</head>


<div style="margin-left:15%">

    <div class="w3-container w3-light-blue">
        <h1>Manage Matakuliah</h1>
    </div>

    <div class="w3-container">
        <div class="container">
            <form action="{{ route('cariSemesterM') }}" method="post" id="semesterForm">
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

                </div>
            </form>
        </div>
    </div>
    <div style="margin-left:10px; margin-right:10px;">

        @if(session('semester')!= 0)
        <h2 class="mt-5">Daftar Mata Kuliah</h2>
        <table class="table table-bordered table-light table-striped my-3 ">
            <thead class="table-dark">
                <input type="hidden" name="id_semester" value="1">
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
                        <form action="{{ route('matakuliahM')}}" method="post">
                            @csrf
                            <div class="d-flex">
                                <input type="hidden" name="matakuliah_id" value="{{ $matakuliahs->id }}">
                                <button type="submit" name="edit" value="1" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;">Edit</button>
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </div>
                        </form>
                    </td>

                </tr>
                @endforeach
               
            </tbody>
        </table>

        @endif
        <button class="buttonadd" type="button" id="adressBTN" onclick="openRegisterForm()">Tambah Mata kuliah</button>
    </div>

    <div id="registerForm" class="register-form">

        <form action="/register" method="post">

            @csrf
            <h1>Tambah Mata kuliah</h1>
            <label class="form-label mt-3">Nama Mata kuliah:</label>
            <input type="text" name="nama" placeholder="ex: Biologi" style="width: 100%;" required>
            <label class="form-label mt-3">Pili alamat:</label><br>
            <div id="addresses">
                <div class="address">
                    <select class="form-control jenjang2" name="jenjang2[]">
                        <option value="">-- Pilih Janjang --</option>
                        @foreach ($jenjang as $jj)
                        <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                        @endforeach
                    </select>
                    <select class="form-control fakultas2" name="fakultas2[]">
                        <option value="">-- Pilih Fakultas --</option>
                    </select>
                    <select class="form-control prodi2" name="prodi2[]">
                        <option value="">-- Pilih Prodi --</option>
                    </select>
                    <select class="form-control semester2" name="semester2[]">
                        <option value="">-- Pilih Semester --</option>
                    </select>
                </div>
            </div>
            <button type="button" id="addAddressButton">Tambah Alamat</button>

            <button type="submit" class="registerbtn">Submit</button>
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

    function addAddress() {
        var container = document.getElementById("addresses");
        var addressInput = document.createElement("div");
        addressInput.innerHTML = '<select class="form-control" id="jenjang2"> <option value="">-- Pilih Janjang --</option> @foreach ($jenjang as $jj) <option value="{{ $jj->id }}">{{ $jj->nama }}</option> @endforeach </select> <select class="form-control" id="fakultas2"> <option value="">-- Pilih Fakultas --</option> @foreach ($fakultas as $f) <option value="{{ $f->id }}">{{ $f->nama }}</option> @endforeach </select> <select class="form-control" id="prodi2"> <option value="">-- Pilih Prodi --</option> @foreach ($prodi as $p) <option value="{{ $p->id }}">{{ $p->nama }}</option>@endforeach </select> <select class="form-control" id="semester2"> <option value="">-- Pilih Semester --</option> @foreach ($semester as $s) <option value="{{ $s->id }}">{{ $s->nama }}</option> @endforeach </select>';

        container.appendChild(addressInput);

    }
</script>
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
<script>
    $(document).ready(function() {
        // Function to attach event listeners to newly added selects
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
                        fakultasSelect.html('<option value="">-- Pilih Fakultas --</option>');
                        $.each(result.fakultas, function(key, value) {
                            fakultasSelect.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                        parent.find('.prodi2').html('<option value="">-- Pilih Prodi --</option>');
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
                        prodiSelect.html('<option value="">-- Pilih Prodi --</option>');
                        $.each(result.prodi, function(key, value) {
                            prodiSelect.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                        parent.find('.semester2').html('<option value="">-- Pilih Semester --</option>');
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
                        semesterSelect.html('<option value="">-- Pilih Semester --</option>');
                        $.each(result.semester, function(key, value) {
                            semesterSelect.append('<option value="' + value.id + '">' + value.nama + '</option>');
                        });
                    }
                });
            });
        }

        // Initial call to attach listeners to existing selects
        attachListeners();

        // Add address function to add new address selects and attach listeners
        function addAddress() {
            var container = $('#addresses');
            var addressInput = `
            <div class="address">
                <select class="form-control jenjang2" name="jenjang2[]">
                    <option value="">-- Pilih Janjang --</option>
                    @foreach ($jenjang as $jj)
                    <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                    @endforeach
                </select>
                <select class="form-control fakultas2" name="fakultas2[]">
                    <option value="">-- Pilih Fakultas --</option>
                </select>
                <select class="form-control prodi2" name="prodi2[]">
                    <option value="">-- Pilih Prodi --</option>
                </select>
                <select class="form-control semester2" name="semester2[]">
                    <option value="">-- Pilih Semester --</option>
                </select>
            </div>`;
            container.append(addressInput);
            attachListeners(); // Re-attach listeners to new elements
        }

        // Attach addAddress function to the button
        $('#addAddressButton').on('click', addAddress);
    });
</script>



@endsection