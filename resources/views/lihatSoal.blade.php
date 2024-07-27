@extends('navbar')
@section('body')

<head>
    <link rel="stylesheet" href="/css/komentarSoal.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .leftcolumn {
            float: left;
            width: 70%;
        }

        .rightcolumn {
            float: left;
            width: 30%;
            height: 100%;
            padding-left: 10px;
            padding-right: 20px;
        }

        .card {
            background-color: white;
            padding: 20px;
            margin-top: 20px;
        }

        input[type=text] {
            border: none;
            background-color: transparent;
            border-bottom: 2px solid rgb(124, 122, 122);
        }

        .comment-box {
            position: relative;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }

        .comment-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .file-upload {
            cursor: pointer;
        }

        .emoji-picker {
            background: none;
            border: none;
            cursor: pointer;
        }

        .fa-image,
        .fa-paper-plane {
            font-size: 18px;
            color: #555;
        }

        .image-preview {
            margin-top: 10px;
            max-height: 200px;
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .image-preview div {
            position: relative;
            display: inline-block;
        }

        .image-preview img {
            max-width: 100px;
            max-height: 100px;
            cursor: pointer;
        }

        .remove-icon {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            cursor: pointer;
        }

        @media screen and (max-width: 800px) {
            .leftcolumn,
            .rightcolumn {
                width: 100%;
                padding: 0;
            }
        }

        .comment-images {
            display: flex;
            flex-wrap: nowrap;
            gap: 10px;
            align-items: center;
            justify-content: flex-start;
        }

        .comment-images a,
        .more-images {
            flex: 1;
            text-align: center;
            overflow: hidden;
        }

        .comment-images img {
            width: 100%;
            height: auto;
            display: block;
        }

        .more-images {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 24px;
            cursor: pointer;
            text-align: center;
            position: relative;
            width: 100px;
            height: 50px;
            border-radius: 4px;
        }

        /* Popup Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
            width: auto;
            height: auto;
        }

        .modal-content, .close, .prev, .next {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {transform: scale(0)} 
            to {transform: scale(1)}
        }

        .close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            z-index: 1060;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -50px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            transition: 0.3s;
            user-select: none;
            z-index: 1060;
        }

        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
        }
    </style>
</head>

<div class="row">
    <div class="leftcolumn">
        <div class="card">
            @foreach ($soal as $soals)
            <h2>{{ $soals->nama_soal }}</h2>
            @if ($soals->tipe == 1)
            <iframe src="{{ Storage::url('pdf/' . $soals->isi_soal) }}" height="600"></iframe>
            @elseif ($soals->tipe == 2)
            <iframe src="{{ Storage::url('html/' . $soals->isi_soal) }}" height="600"></iframe>
            @endif
            @endforeach
        </div>
    </div>
    <div class="rightcolumn">
        <div class="container mt-4">
            <h2>Komentar</h2>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <form class="mt-2" action="/komentar" method="post" enctype="multipart/form-data" id="comment-form">
                @csrf
                <input type="hidden" name="id_soal" value="{{ $soals->id }}">
                <div class="comment-box">
                    <textarea class="mb-5" name="isi_komentar" placeholder='Isi Komentar...'></textarea>
                    <div class="comment-actions">
                        <label for="file-upload" class="file-upload">
                            <input type="file" id="file-upload" name="file_komentar[]" accept="image/*" multiple style="display: none;" onchange="previewImages(event)">
                            <i class="fa fa-image"></i>
                        </label>
                        <button type="button" class="btn btn-info text-light" onclick="submitForm()">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                    <div class="image-preview" id="image-preview"></div>
                </div>
            </form>
        </div>

       @foreach ($komentar_soal as $komentar)
<div class="post">
    <div class="post-header">
        <div class="user">{{ $user->get($komentar->id_user)->nama }}</div>
        <div class="rating">4.0 ★★★★☆(58)</div>
    </div>
    <div class="post-content">{{ $komentar->isi_komentar }}</div>
    @if ($komentar->file_komentar)
    <div class="comment-images">
        @php
        $images = json_decode($komentar->file_komentar);
        $imageCount = count($images);
        @endphp

        @foreach ($images as $index => $image)
        @if ($index < 2)
        <a href="javascript:void(0)" onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}', {{$index}}, {{ json_encode($images) }})">
            <img src="{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}" alt="Comment Image">
        </a>
        @endif
        @endforeach

        @if ($imageCount > 2)
        <div class="more-images" onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $images[2]) }}', 2, {{ json_encode($images) }})">+{{ $imageCount - 2 }}</div>
        @endif

        <!-- Hidden images for Lightbox -->
        @foreach ($images as $index => $image)
        @if ($index >= 2)
        <a href="javascript:void(0)" onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}', {{$index}}, {{ json_encode($images) }})" style="display:none;">
            <img src="{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}" alt="Comment Image">
        </a>
        @endif
        @endforeach
    </div>
    @endif
    <div class="post-footer">
        <div class="rating">
            <span data-rating="1">★</span>
            <span data-rating="2">★</span>
            <span data-rating="3">★</span>
            <span data-rating="4">★</span>
            <span data-rating="5">★</span>
        </div>
        <div class="comment-button">Comment</div>
    </div>
</div>
@endforeach


    </div>
</div>

<!-- The Modal -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeImageModal()">&times;</span>
    <span class="prev" onclick="changeImage(-1)">&#10094;</span>
    <span class="next" onclick="changeImage(1)">&#10095;</span>
    <div class="modal-content-wrapper">
        <img class="modal-content" id="modalImage">
    </div>
</div>

<script>
    let imageFiles = [];
    let currentImageIndex = 0;
    let imageList = [];

    function previewImages(event) {
        const files = event.target.files;
        const preview = document.getElementById('image-preview');

        Array.from(files).forEach(file => {
            if (!imageFiles.some(existingFile => existingFile.name === file.name && existingFile.size === file.size)) {
                imageFiles.push(file);
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.style.position = 'relative';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Image Preview';

                    const removeIcon = document.createElement('span');
                    removeIcon.className = 'remove-icon';
                    removeIcon.innerHTML = '&times;';
                    removeIcon.onclick = function() {
                        const index = imageFiles.indexOf(file);
                        if (index > -1) {
                            imageFiles.splice(index, 1);
                        }
                        imgContainer.remove();
                    };

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeIcon);
                    preview.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            }
        });

        event.target.value = '';
    }

    function submitForm() {
        const form = document.getElementById('comment-form');
        const formData = new FormData(form);

        imageFiles.forEach(file => {
            formData.append('file_komentar[]', file);
        });

        fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = "/lihatsoal?soals_id=" + form.querySelector('input[name="id_soal"]').value;
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    function openImageModal(src, index, images) {
        currentImageIndex = index;
        imageList = images.map(img => "{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/') }}" + img);

        var modal = document.getElementById("imageModal");
        var modalImg = document.getElementById("modalImage");
        modal.style.display = "block";
        modalImg.src = src;
        modalImg.style.maxWidth = "80%";
        modalImg.style.maxHeight = "80vh";
    }

    function closeImageModal() {
        var modal = document.getElementById("imageModal");
        modal.style.display = "none";
    }

    function changeImage(direction) {
        currentImageIndex += direction;
        if (currentImageIndex < 0) {
            currentImageIndex = imageList.length - 1;
        } else if (currentImageIndex >= imageList.length) {
            currentImageIndex = 0;
        }
        var modalImg = document.getElementById("modalImage");
        modalImg.src = imageList[currentImageIndex];
    }
</script>
@endsection
