@extends('navbar')
@section('body')

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>{{ session('namamk') }}</title>
    <link rel="stylesheet" href="/css/popupform.css">
    <link rel="stylesheet" href="/css/komentarSoal.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            box-sizing: border-box;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .container {
            display: flex;
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
            box-sizing: border-box;
        }


        .content {
            flex-grow: 1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-right: 20px;

        }
    </style>
</head>

<body>
    <div class="header">
        <h2>{{ session('namamk') }}</h2>
    </div>
    <div class="container">
        <div class="content">
            <div class="button-container">
                <button class="buttonadd mt-3" type="button" onclick="openRegisterForm()"> Buat postingan Baru</button>
            </div>
            @foreach ($posts as $post)
                <div class="post">
                    <div class="post-header">
                        <div class="user">{{ $userPost->get($post->id_user)->nama }}</div>
                        <div class="lovecount">♥</div>
                    </div>
                    <div class="post-content">
                        {{ $post->isi_post }}
                    </div>
                    <div class="post-footer">
                        <div class="love">
                            <span data-rating="1">♥</span>
                        </div>
                        <div class="comment-button" onclick="showCommentForm({{ $post->id }})">Komentar</div>
                    </div>
                    <div class="comment-form mt-2" id="comment-form-{{ $post->id }}">
                        <form action="/komentar-post" method="post" enctype="multipart/form-data" class="comment-post-form"
                            id="comment-post-form-{{ $post->id }}">
                            @csrf
                            <input type="hidden" name="id_post" value="{{ $post->id }}">
                            <div class="comment-box">
                                <textarea class="mb-2 mt-3" name="isi_komentar" placeholder='Isi Komentar...'></textarea>
                                <div class="comment-actions">
                                    <label for="file-upload-{{ $post->id }}" class="file-upload">
                                        <input type="file" id="file-upload-{{ $post->id }}" name="file_komentar[]"
                                            accept="image/*" multiple style="display: none;"
                                            onchange="previewImages2(event)">
                                        <i class="fa fa-image"></i>
                                    </label>
                                    <button type="button" class="btn btn-info text-light"
                                        onclick="submitForm2({{ $post->id }})">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                                <div class="image-preview" id="image-preview-{{ $post->id }}"></div>
                            </div>
                        </form>
                    </div>
                    <div id="comments-container">
                        @foreach ($komentar_parents as $komentar)
                                    <div class="post" id="post-{{ $komentar->id }}">
                                        <div class="post-header">
                                            <div class="user">
                                                <span style="font-weight:bold;">{{ $user->get($komentar->id_user)->nama }}</span>
                                            </div>
                                            <div class="avg_rating" data-komentar-id="{{ $komentar->id }}">
                                                <span>★</span>
                                                <span>{{ isset($ratingData[$komentar->id]) ? number_format($ratingData[$komentar->id]->avg_rating, 1) : '0.0' }}</span>
                                                <span>({{ isset($ratingData[$komentar->id]) ? $ratingData[$komentar->id]->rating_count : '0' }})</span>
                                            </div>
                                        </div>
                                        <div class="user-level">
                                            @if($user->get($komentar->id_user)->level == 1)
                                                <span>Admin</span>
                                            @elseif($user->get($komentar->id_user)->level == 2)
                                                <span>Dosen</span>
                                            @elseif($user->get($komentar->id_user)->level == 3)
                                                <span>Mahasiswa</span>
                                            @endif
                                            <span>{{ \Carbon\Carbon::parse($komentar->updated_at)->locale('id')->diffForHumans() }}</span>
                                        </div>
                                        <div class="post-content">{{ $komentar->isi_komentar }}</div>
                                        @if ($komentar->file_komentar)
                                                        <div class="comment-images">
                                                            @php
                                                                $images = json_decode($komentar->file_komentar);
                                                                $imageCount = count($images);
                                                            @endphp
                                                            @foreach ($images as $index => $image)
                                                                @if ($index < 2) <a href="javascript:void(0)"
                                                                        onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}', {{$index}}, {{ json_encode($images) }})">
                                                                        <img src="{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}"
                                                                            alt="Comment Image">
                                                                    </a>
                                                                @endif
                                                            @endforeach
                                                            @if ($imageCount > 2)
                                                                <div class="more-images"
                                                                    onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $images[2]) }}', 2, {{ json_encode($images) }})">
                                                                    +{{ $imageCount - 2 }}</div>
                                                            @endif
                                                            @foreach ($images as $index => $image)
                                                                @if ($index >= 2)
                                                                    <a href="javascript:void(0)"
                                                                        onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}', {{$index}}, {{ json_encode($images) }})"
                                                                        style="display:none;">
                                                                        <img src="{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}"
                                                                            alt="Comment Image">
                                                                    </a>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                        @endif
                                        <div class="post-footer">
                                            <div class="rating" data-komentar-id="{{ $komentar->id }}">
                                                @for ($i = 1; $i <= 5; $i++) <span data-rating="{{ $i }}"
                                                    class="{{ isset($ratings[$komentar->id]) && $ratings[$komentar->id]->rating >= $i ? 'selected' : '' }}">★</span>
                                                @endfor
                                            </div>
                                            <div class="comment-button" onclick="showReplyForm({{ $komentar->id }})">Balas</div>
                                            @if(auth()->check() && auth()->user()->level == 1 || auth()->user()->id == $komentar->id_user)
                                                <div class="delete-button" onclick="deleteComment({{ $komentar->id }})">Delete</div>
                                            @else
                                                <div class="delete-button disabled">Delete</div>
                                            @endif
                                        </div>

                                        <div class="reply-form" id="reply-form-{{ $komentar->id }}">
                                            <form action="/komentar-post" method="post" enctype="multipart/form-data"
                                                class="reply-comment-form" id="reply-comment-form-{{ $komentar->id }}">
                                                @csrf
                                                <input type="hidden" name="id_post" value="{{ $post->id }}">
                                                <input type="hidden" name="parent_id" value="{{ $komentar->id }}">
                                                <div class="comment-box">
                                                    <textarea class="mb-2 mt-3" name="isi_komentar"
                                                        placeholder='Isi Komentar...'></textarea>
                                                    <div class="comment-actions">
                                                        <label for="file-upload-{{ $komentar->id }}" class="file-upload">
                                                            <input type="file" id="file-upload-{{ $komentar->id }}"
                                                                name="file_komentar[]" accept="image/*" multiple style="display: none;"
                                                                onchange="previewImages2(event)">
                                                            <i class="fa fa-image"></i>
                                                        </label>
                                                        <button type="button" class="btn btn-info text-light"
                                                            onclick="submitForm3({{ $komentar->id }})">
                                                            <i class="fa fa-paper-plane"></i>
                                                        </button>
                                                    </div>
                                                    <div class="image-preview" id="image-preview-{{ $komentar->id }}"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @if (isset($komentar_replies[$komentar->id]))
                                        <div class="replies">
                                            @foreach ($komentar_replies[$komentar->id] as $reply)
                                                    <div class="comment" id="post-reply-{{ $reply->id }}">
                                                        <div class="comment-header">
                                                            <div class="user">
                                                                <span>{{ $user->get($reply->id_user)->nama }}</span>
                                                            </div>
                                                            <div class="avg_rating" data-komentar-id="{{ $reply->id }}">
                                                                <span>★</span>
                                                                <span>{{ isset($ratingData[$reply->id]) ? number_format($ratingData[$reply->id]->avg_rating, 1) : '0.0' }}</span>
                                                                <span>({{ isset($ratingData[$reply->id]) ? $ratingData[$reply->id]->rating_count : '0' }})</span>
                                                            </div>
                                                        </div>
                                                        <div class="user-level">
                                                            @if($user->get($reply->id_user)->level == 1)
                                                                Admin
                                                            @elseif($user->get($reply->id_user)->level == 2)
                                                                Dosen
                                                            @elseif($user->get($reply->id_user)->level == 3)
                                                                Mahasiswa
                                                            @endif
                                                            <span>{{ \Carbon\Carbon::parse($reply->updated_at)->locale('id')->diffForHumans() }}</span>
                                                        </div>
                                                        <div class="comment-content">{{ $reply->isi_komentar }}</div>
                                                        @if ($reply->file_komentar)
                                                                    <div class="comment-images">
                                                                        @php
                                                                            $images = json_decode($reply->file_komentar);
                                                                            $imageCount = count($images);
                                                                        @endphp
                                                                        @foreach ($images as $index => $image)
                                                                            @if ($index < 2) <a href="javascript:void(0)"
                                                                                    onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $image) }}', {{$index}}, {{ json_encode($images) }})">
                                                                                    <img src="{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $image) }}"
                                                                                        alt="Comment Image">
                                                                                </a>
                                                                            @endif
                                                                        @endforeach
                                                                        @if ($imageCount > 2)
                                                                            <div class="more-images"
                                                                                onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $images[2]) }}', 2, {{ json_encode($images) }})">
                                                                                +{{ $imageCount - 2 }}</div>
                                                                        @endif
                                                                        @foreach ($images as $index => $image)
                                                                            @if ($index >= 2)
                                                                                <a href="javascript:void(0)"
                                                                                    onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $image) }}', {{$index}}, {{ json_encode($images) }})"
                                                                                    style="display:none;">
                                                                                    <img src="{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $image) }}"
                                                                                        alt="Comment Image">
                                                                                </a>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                        @endif
                                                        <div class="comment-footer">
                                                            <div class="rating" data-komentar-id="{{ $reply->id }}">
                                                                @for ($i = 1; $i <= 5; $i++) <span data-rating="{{ $i }}"
                                                                    class="{{ isset($ratings[$reply->id]) && $ratings[$reply->id]->rating >= $i ? 'selected' : '' }}">★</span>
                                                                @endfor
                                                            </div>
                                                            @if(auth()->check() && auth()->user()->level == 1)
                                                                <div class="delete-button" onclick="deleteComment({{ $reply->id }})">Delete</div>
                                                            @else
                                                                <div class="delete-button disabled">Delete</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                            @endforeach
                                        </div>
                                    @endif
                        @endforeach
                    </div>
                </div>
            @endforeach


        </div>
    </div>
    <div id="registerForm" class="register-form">

        <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data" id="post-form">

            @csrf
            <h1>Buat Postingan Baru</h1>
            <input type="hidden" name="id_mk" value="{{ session('id_matakuliah') }}">
            <textarea class="mb-2 mt-3" name="isi_post" placeholder='Isi Post...'></textarea>
            <div class="comment-actions">
                <label for="file-upload" class="file-upload">
                    <input type="file" id="file-upload" name="file_post[]" accept="image/*" multiple
                        style="display: none;" onchange="previewImages(event)">
                    <i class="fa fa-image"></i>
                </label>
                <button type="button" class="btn btn-info text-light" onclick="submitForm()">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
            <div class="image-preview" id="image-preview"></div>


            <button class="buttoncancel mt-3" type="button" onclick="closeRegisterForm()">Batal</button>
        </form>
    </div>
</body>
<script>
    let imageFiles = [];

    function previewImages(event) {
        const files = event.target.files;
        const preview = document.getElementById('image-preview');

        Array.from(files).forEach(file => {
            if (!imageFiles.some(existingFile => existingFile.name === file.name && existingFile.size === file.size)) {
                imageFiles.push(file);
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.style.position = 'relative';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Image Preview';

                    const removeIcon = document.createElement('span');
                    removeIcon.className = 'remove-icon';
                    removeIcon.innerHTML = '&times;';
                    removeIcon.onclick = function () {
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
    function openRegisterForm() {
        document.getElementById("registerForm").style.display = "block";
    }

    function closeRegisterForm() {
        document.getElementById("registerForm").style.display = "none";
    }

    function submitForm() {
        const form = document.getElementById('post-form');
        const formData = new FormData(form);
        const commentInput = form.querySelector('textarea[name="isi_post"]').value.trim();
        const maxFileSize = 2048 * 1024; // ขนาดไฟล์สูงสุดในหน่วย bytes (2048 KB)
        let isValid = true;

        // ตรวจสอบว่ามีการเขียนคอมเม้นหรือไม่
        if (commentInput === '') {
            alert('กรุณาเขียนคอมเม้น');
            isValid = false;
        }

        // ตรวจสอบขนาดไฟล์
        imageFiles.forEach(file => {
            if (file.size > maxFileSize) {
                alert('ไฟล์ขนาดใหญ่เกินไป กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 2 MB');
                isValid = false;
            }
            formData.append('file_post[]', file);
        });

        // หากข้อมูลถูกต้องให้ส่งฟอร์ม
        if (isValid) {
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        form.reset();
                        imageFiles = [];
                        document.getElementById('image-preview').innerHTML = '';
                        loadComments();
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    }
    function submitForm2(id) {
        const form = document.getElementById('comment-post-form-' + id);
        const formData = new FormData(form);
        const commentInput = form.querySelector('textarea[name="isi_komentar"]').value.trim();
        const maxFileSize = 2048 * 1024; // ขนาดไฟล์สูงสุดในหน่วย bytes (2048 KB)
        let isValid = true;

        // ตรวจสอบว่ามีการเขียนคอมเม้นหรือไม่
        if (commentInput === '') {
            alert('กรุณาเขียนคอมเม้น');
            isValid = false;
        }

        // ตรวจสอบขนาดไฟล์
        imageFiles.forEach(file => {
            if (file.size > maxFileSize) {
                alert('ไฟล์ขนาดใหญ่เกินไป กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 2 MB');
                isValid = false;
            }
            formData.append('file_komentar[]', file);
        });

        // หากข้อมูลถูกต้องให้ส่งฟอร์ม
        if (isValid) {
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        form.reset();
                        imageFiles = [];
                        document.getElementById('image-preview-' + id).innerHTML = '';
                        loadComments();
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    }
    function submitForm3(id) {
        const form = document.getElementById('reply-comment-form-' + id);
        const formData = new FormData(form);
        const commentInput = form.querySelector('textarea[name="isi_komentar"]').value.trim();
        const maxFileSize = 2048 * 1024; // ขนาดไฟล์สูงสุดในหน่วย bytes (2048 KB)
        let isValid = true;

        // ตรวจสอบว่ามีการเขียนคอมเม้นหรือไม่
        if (commentInput === '') {
            alert('กรุณาเขียนคอมเม้น');
            isValid = false;
        }

        // ตรวจสอบขนาดไฟล์
        imageFiles.forEach(file => {
            if (file.size > maxFileSize) {
                alert('ไฟล์ขนาดใหญ่เกินไป กรุณาเลือกไฟล์ที่มีขนาดไม่เกิน 2 MB');
                isValid = false;
            }
            formData.append('file_komentar[]', file);
        });

        // หากข้อมูลถูกต้องให้ส่งฟอร์ม
        if (isValid) {
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        form.reset();
                        imageFiles = [];
                        document.getElementById('image-preview-' + id).innerHTML = '';
                        loadComments();
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }
    }
    function showCommentForm(postId) {
        document.querySelectorAll('.comment-form').forEach(form => form.style.display = 'none');
        document.getElementById('comment-form-' + postId).style.display = 'block';
    }
    function showReplyForm(komentarId) {
        document.querySelectorAll('.reply-form').forEach(form => form.style.display = 'none');
        document.getElementById('reply-form-' + komentarId).style.display = 'block';
    }

    document.querySelectorAll('.rating span').forEach(star => {
        star.addEventListener('click', function () {
            let rating = this.getAttribute('data-rating');
            let stars = this.parentNode.children;
            for (let i = 0; i < stars.length; i++) {
                if (i < rating) {
                    stars[i].classList.add('selected');
                } else {
                    stars[i].classList.remove('selected');
                }
            }
        });
        star.addEventListener('mouseover', function () {
            let rating = this.getAttribute('data-rating');
            let stars = this.parentNode.children;
            for (let i = 0; i < stars.length; i++) {
                if (i < rating) {
                    stars[i].classList.add('hover');
                } else {
                    stars[i].classList.remove('hover');
                }
            }
        });
        star.addEventListener('mouseout', function () {
            let stars = this.parentNode.children;
            for (let i = 0; i < stars.length; i++) {
                stars[i].classList.remove('hover');
            }
        });
    });
</script>

@endsection