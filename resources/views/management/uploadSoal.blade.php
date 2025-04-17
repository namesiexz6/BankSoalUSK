<!DOCTYPE html>
<html lang="app()->getLocale()">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.1.1/tinymce.min.js" integrity="sha512-bAtLCmEwg+N9nr6iVELr/SlDxBlyoF0iVdPxAvcOCfUiyi6RcuS6Lzawi78iPbAfbNyIUftvwK9HPWd+3p975Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="/css/popupform.css">
    <title>Upload Soal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 1500px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>

</head>

<body>

    <div class="w3-container">
        <div class="container">
            <form id="uploadForm" action="{{ route('tambahSoalM') }}" method="post" enctype="multipart/form-data">

                <div class="row">

                    <div class="col-md-3">
                        @csrf
                        <h1>{{ __('management.tambah_soal') }}</h1>

                        <label class="form-label mt-3">{{ __('management.nama_soal') }}:</label><br>
                        <input class="form-control" name="nama_soal" id="nama_soal" type="text" placeholder="ex : Latihan 1" required>
                    </div>
                    <div class="col-md-3">
                        <div id="fileOptions" class="tab">
                            <button class="tablinks active" onclick="toggleFileOption(event, 'upload')">{{ __('management.upload_file') }}</button>
                            <button class="tablinks" onclick="toggleFileOption(event, 'textarea')">{{ __('management.text_editor') }}</button>
                        </div>

                        <!-- input file และ textarea -->
                        <div id="upload" class="tabcontent" style="display: block;">
                            <label class="form-label mt-3">{{ __('management.pilih_file') }}:</label>
                            <input class="form-control" type="file" name="formFile" id="formFile" accept=".pdf">
                        </div>
                        <div id="textarea" class="tabcontent" style="display: none;">
                            <textarea class="form-control" id="textareaContent" name="textareaContent"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3"><br>
                        <label for="jenjang2" class="form-label mt-3">{{ __('management.pilih_alamat') }}:</label> <br>
                        <select class="form-control" aria-label="Default select" name="jenjang2" id="jenjang2">
                            <option value="">-- {{ __('management.pilih_jenjang') }} --</option>
                            @foreach ($jenjang as $jj)
                            <option value="{{ $jj->id }}">{{ $jj->nama }}</option>
                            @endforeach
                        </select>
                        <select class="form-control" aria-label="Default select" name="fakultas2" id="fakultas2">
                            <option value="">-- {{ __('management.pilih_fakultas') }} --</option>
                            @foreach ($fakultas as $f)
                            <option value="{{ $f->id }}">{{ $f->nama }}</option>
                            @endforeach
                        </select>
                        <select class="form-control" aria-label="Default select" name="prodi2" id="prodi2">
                            <option value="">-- {{ __('management.pilih_prodi') }} --</option>
                            @foreach ($prodi as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        <select class="form-control" aria-label="Default select" name="semester2" id="semester2">
                            <option value="">-- {{ __('management.pilih_semester') }} --</option>
                            @foreach ($semester as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                            @endforeach
                        </select>
                        <select class="form-control" aria-label="Default select" name="matakuliah2" id="matakuliah2" required>
                            <option value="">-- {{ __('management.pilih_matakuliah') }} --</option>
                            @foreach ($matakuliah as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="buttonadd mt-3" id="submitButton" type="submit">{{ __('management.submit') }}</button>
                    <button class="buttoncancel mt-3" type="button" id="backButton">{{ __('management.batal') }}</button>
                </div>
            </form>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        document.getElementById('backButton').addEventListener('click', function() {
            var confirmed = confirm('Are you sure you want to leave this page?');
            if (confirmed) {
                window.history.back();
            }
        });

        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            var fileInput = document.getElementById('formFile');
            var textareaContent = tinymce.get("textareaContent").getContent();
            var file = fileInput.files[0];
            var maxSize = 5 * 1024 * 1024; // 5MB in bytes

            // Check if neither file nor textarea is filled
            if (!file && !textareaContent.trim()) {
                alert('Please upload a file or enter text in the editor.');
                event.preventDefault();
                return;
            }

            if (file) {
                if (file.size > maxSize) {
                    alert('File size exceeds 5MB. Please upload a smaller file.');
                    event.preventDefault();
                    return;
                }

                var fileReader = new FileReader();
                fileReader.onload = function(e) {
                    var fileContent = e.target.result;

                    if (fileContent.trim().length === 0) {
                        alert('File is empty. Please upload a valid PDF file.');
                        event.preventDefault();
                    } else {
                        document.getElementById('uploadForm').submit();
                    }
                };
                fileReader.readAsText(file);

                event.preventDefault();
            } else {
                // If no file, check textarea content
                if (textareaContent.trim().length === 0) {
                    alert('The text editor content is empty. Please enter some text.');
                    event.preventDefault();
                }
            }
        });
    </script>
    <script>
        function toggleFileOption(evt, optionName) {
            evt.preventDefault();
            var tabcontent = document.getElementsByClassName("tabcontent");
            var tablinks = document.getElementsByClassName("tablinks");

            var fileInput = document.getElementById("formFile");
            var textareaContent = tinymce.get("textareaContent").getContent();

            var showPopup = false;
            var popupMessage = "";

            if (optionName === "textarea" && fileInput.files.length > 0) {
                showPopup = true;
                popupMessage = "You have an uploaded file. Are you sure you want to switch and discard the file?";
            } else if (optionName === "upload" && textareaContent.trim() !== "") {
                showPopup = true;
                popupMessage = "You have content in the Editor. Are you sure you want to switch and discard the content?";
            }

            if (showPopup) {
                var confirmed = confirm(popupMessage);
                if (!confirmed) {
                    return;
                } else {
                    if (optionName === "textarea") {
                        fileInput.value = "";
                    } else if (optionName === "upload") {
                        tinymce.get("textareaContent").setContent("");
                    }
                }
            }

            for (var i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            for (var i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(optionName).style.display = "block";
            evt.currentTarget.className += " active";
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
                        fakultas.innerHTML = '<option value="">-- {{ __('management.pilih_fakultas') }} --</option>';
                        console.log(result);
                        $.each(result.fakultas, function(key, value) {
                            fakultas.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                        });
                        prodi.innerHTML = '<option value="">-- {{ __('management.pilih_prodi') }} --</option>';
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
                        prodi.innerHTML = '<option value="">-- {{ __('management.pilih_prodi') }} --</option>';
                        console.log(result);
                        $.each(result.prodi, function(key, value) {
                            prodi.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                        });
                        semester.innerHTML = '<option value="">-- {{ __('management.pilih_semester') }} --</option>';
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
                        semester.innerHTML = '<option value="">-- {{ __('management.pilih_semester') }} --</option>';
                        console.log(result);
                        $.each(result.semester, function(key, value) {
                            semester.innerHTML += '<option value="' + value.id + '">' + value.nama + '</option>';
                        });
                        matakuliah.innerHTML = '<option value="">-- {{ __('management.pilih_matakuliah') }} --</option>';
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
                        matakuliah.innerHTML = '<option value="">-- {{ __('management.pilih_matakuliah') }} --</option>';
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
</body>

</html>
