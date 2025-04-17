@extends('navbar')
@section('body')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>{{ session('namamk') }}</title>
    <link rel="stylesheet" href="/css/popupform.css">
    <link rel="stylesheet" href="/css/komentarSoal.css">
    <style>
        .nav-link {
            font-size: 18px;
        }

        .navbar-brand img {
            width: 120px;
        }

        .header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            box-sizing: border-box;
        }

        .delete-button.disabled {
            cursor: not-allowed;
            color: grey;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .love-button {
            background: none;
            border: none;
            cursor: pointer;
            color: #ffcccc;
            font-size: 24px;
            transition: color 0.3s ease;
            vertical-align: middle;
            padding: 0;
        }

        .love-button.loved {
            color: #ff4d4d;
        }

        .love-count {
            margin-left: 5px;
            font-weight: bold;
            font-size: 18px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="background" style="background-image: url('{{ asset('background.png') }}'); background-size: cover; background-position: top; height: 20vh; display: flex; align-items: center; justify-content: center;">
    @if(app()->getLocale() == 'en')    
    <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;">{{ session('namamk') }} {{ __('post.matakuliah') }}</h2>
    @else
    <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;">{{ __('post.matakuliah') }} {{ session('namamk') }}</h2>
    @endif
    </div>
    <div class="container" id="post-container">
        <div class="content">
            <div class="button-container">
                <button class="buttonadd mt-3" type="button" onclick="openRegisterForm()" style="background-color: #134F5C">{{ __('post.buat_postingan_baru') }}</button>
            </div>
            <select class="sort-comments mt-3 mb-2" id="sort-posts" onchange="sortPosts()">
                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>{{ __('post.terbaru') }}</option>
                <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>{{ __('post.terlama') }}</option>
                <option value="most_rated" {{ $sort == 'most_rated' ? 'selected' : '' }}>{{ __('post.terbanyak_rating') }}</option>
            </select>
            <div id="post-container2">
                @foreach ($posts as $post)
                <div class="post" id="post-{{ $post->id }}">
                    <div class="post-header">
                        <div class="user">{{ $userPost->get($post->id_user)->nama }}</div>
                    </div>
                    <div class="user-level">
                        @if($userPost->get($post->id_user)->level == 1)
                        {{ __('post.admin') }}
                        @elseif($userPost->get($post->id_user)->level == 2)
                        {{ __('post.dosen') }}
                        @elseif($userPost->get($post->id_user)->level == 3)
                        {{ __('post.mahasiswa') }}
                        @endif
                        <span>{{ \Carbon\Carbon::parse($post->updated_at)->locale(app()->getLocale())->diffForHumans() }}</span>
                    </div>
                    <div class="post-content">{{ $post->isi_post }}</div>
                    @if ($post->file_post)
                    <div class="post-images">
                        @php
                        $images = json_decode($post->file_post);
                        $imageCount = count($images);
                        @endphp
                        @foreach ($images as $index => $image)
                        @if ($index < 2)
                        <a href="javascript:void(0)" onclick="openImageModalPost('{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ session('id_matakuliah') }})">
                            <img src="{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}" alt="Comment Image">
                        </a>
                        @endif
                        @endforeach
                        @if ($imageCount > 2)
                        <div class="more-image-post" onclick="openImageModalPost('{{ Storage::url('public/post/' . $post->id_mk . '/' . $images[2]) }}', 2, {{ json_encode($images) }}, {{ session('id_matakuliah') }})">
                            +{{ $imageCount - 2 }}
                        </div>
                        @endif
                        @foreach ($images as $index => $image)
                        @if ($index >= 2)
                        <a href="javascript:void(0)" onclick="openImageModalPost('{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}', {{$index}}, {{ json_encode($images) }},{{ session('id_matakuliah') }})" style="display:none;">
                            <img src="{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}" alt="Comment Image">
                        </a>
                        @endif
                        @endforeach
                    </div>
                    @endif
                    <div class="post-footer">
                        <div class="love">
                            <button class="love-button {{ $post->loves->contains('id_user', auth()->id()) ? 'loved' : '' }}" data-post-id="{{ $post->id }}" onclick="toggleLove({{ $post->id }})">
                                <i class="fa fa-heart"></i>
                            </button>
                            <span class="love-count" id="love-count-{{ $post->id }}">{{ $post->loves->count() }}</span>
                        </div>
                        <div class="comment-button" onclick="showCommentForm({{ $post->id }})">{{ __('post.komentar') }}</div>
                        @if(auth()->check() && (auth()->user()->level == 1 || auth()->user()->id == $post->id_user))
                        <div class="delete-button" onclick="deletePost({{ $post->id }})">{{ __('post.delete') }}</div>
                        @else
                        <div class="delete-button disabled">{{ __('post.delete') }}</div>
                        @endif
                    </div>
                    <div class="comment-form" id="comment-form-{{ $post->id }}">
                        <form action="/komentar-post" method="post" enctype="multipart/form-data" class="comment-post-form" id="comment-post-form-{{ $post->id }}">
                            @csrf
                            <input type="hidden" name="id_post" value="{{ $post->id }}">
                            <div class="comment-box">
                                <textarea class="mb-2 mt-3" name="isi_komentar" placeholder='{{ __('post.isi_post') }}'></textarea>
                                <div class="comment-actions">
                                    <label for="file-upload-{{ $post->id }}" class="file-upload">
                                        <input type="file" id="file-upload-{{ $post->id }}" name="file_komentar[]" accept="image/*" multiple style="display: none;" onchange="previewImages2(event)">
                                        <i class="fa fa-image"></i>
                                    </label>
                                    <button type="button" class="btn btn-info text-light" onclick="submitForm2({{ $post->id }})">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>
                                </div>
                                <div class="image-preview" id="image-preview-{{ $post->id }}"></div>
                            </div>
                        </form>
                    </div>
                    @if ($komentar_parents->contains('id_post', $post->id))
                    <div class="sort-comments-container">
                        <select class="sort-comments mt-3 mb-2" id="sort-comments-{{ $post->id }}" onchange="sortComments({{ $post->id }})">
                            <option value="newest">{{ __('post.terbaru') }}</option>
                            <option value="oldest">{{ __('post.terlama') }}</option>
                            <option value="most_rated">{{ __('post.terbanyak_rating') }}</option>
                        </select>
                    </div>
                    @endif
                    <div id="comments-container-{{ $post->id }}" class="comments-container">
                        @foreach ($komentar_parents as $komentar)
                        @if ($komentar->id_post == $post->id)
                        <div class="comment">
                            <div class="comment-header">
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
                                <span>{{ \Carbon\Carbon::parse($komentar->updated_at)->locale(app()->getLocale())->diffForHumans() }}</span>
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
                                <a href="javascript:void(0)" onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $komentar->id_post }})">
                                    <img src="{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $image) }}" alt="Comment Image">
                                </a>
                                @endif
                                @endforeach
                                @if ($imageCount > 2)
                                <div class="more-images" onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $images[2]) }}', 2, {{ json_encode($images) }}, {{ $komentar->id_post }})">
                                    +{{ $imageCount - 2 }}
                                </div>
                                @endif
                                @foreach ($images as $index => $image)
                                @if ($index >= 2)
                                <a href="javascript:void(0)" onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $komentar->id_post }})" style="display:none;">
                                    <img src="{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $image) }}" alt="Comment Image">
                                </a>
                                @endif
                                @endforeach
                            </div>
                            @endif
                            <div class="post-footer">
                                <div class="rating" data-komentar-id="{{ $komentar->id }}">
                                    @for ($i = 1; $i <= 5; $i++)
                                    <span data-rating="{{ $i }}" class="{{ isset($ratings[$komentar->id]) && $ratings[$komentar->id]->rating >= $i ? 'selected' : '' }}">★</span>
                                    @endfor
                                </div>
                                <div class="comment-button" onclick="showReplyForm({{ $komentar->id }})">{{ __('post.balas') }}</div>
                                @if(auth()->check() && (auth()->user()->level == 1 || auth()->user()->id == $komentar->id_user))
                                <div class="delete-button" onclick="deleteComment({{ $komentar->id }})">{{ __('post.delete') }}</div>
                                @else
                                <div class="delete-button disabled">{{ __('post.delete') }}</div>
                                @endif
                            </div>
                            <div class="reply-form" id="reply-form-{{ $komentar->id }}">
                                <form action="/komentar-post" method="post" enctype="multipart/form-data" class="reply-comment-form" id="reply-comment-form-{{ $komentar->id }}">
                                    @csrf
                                    <input type="hidden" name="id_post" value="{{ $komentar->id_post }}">
                                    <input type="hidden" name="parent_id" value="{{ $komentar->id }}">
                                    <div class="comment-box">
                                        <textarea class="mb-2 mt-3" name="isi_komentar" placeholder='{{ __('post.isi_post') }}'></textarea>
                                        <div class="comment-actions">
                                            <label for="file-upload-{{ $komentar->id }}" class="file-upload">
                                                <input type="file" id="file-upload-{{ $komentar->id }}" name="file_komentar[]" accept="image/*" multiple style="display: none;" onchange="previewImages2(event)">
                                                <i class="fa fa-image"></i>
                                            </label>
                                            <button type="button" class="btn btn-info text-light" onclick="submitForm3({{ $komentar->id }},{{ $post->id }})">
                                                <i class="fa fa-paper-plane"></i>
                                            </button>
                                        </div>
                                        <div class="image-preview" id="image-preview-{{ $komentar->id }}"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @if (isset($komentar_replies[$komentar->id]))
                        <div class="replies" id="replies-container-{{ $komentar->id }}">
                            @foreach ($komentar_replies[$komentar->id] as $index => $reply)
                            <div class="reply-comment" style="{{ $index >= 1 ? 'display: none;' : '' }}">
                                <div class="reply-comment-header">
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
                                    <span>{{ \Carbon\Carbon::parse($reply->updated_at)->locale(app()->getLocale())->diffForHumans() }}</span>
                                </div>
                                <div class="comment-content">{{ $reply->isi_komentar }}</div>
                                @if ($reply->file_komentar)
                                <div class="comment-images">
                                    @php
                                    $images = json_decode($reply->file_komentar);
                                    $imageCount = count($images);
                                    @endphp
                                    @foreach ($images as $index => $image)
                                    @if ($index < 2)
                                    <a href="javascript:void(0)" onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $reply->id_post }})">
                                        <img src="{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $image) }}" alt="Comment Image">
                                    </a>
                                    @endif
                                    @endforeach
                                    @if ($imageCount > 2)
                                    <div class="more-images" onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $images[2]) }}', 2, {{ json_encode($images) }}, {{ $reply->id_post }})">
                                        +{{ $imageCount - 2 }}
                                    </div>
                                    @endif
                                    @foreach ($images as $index => $image)
                                    @if ($index >= 2)
                                    <a href="javascript:void(0)" onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $reply->id_post }})" style="display:none;">
                                        <img src="{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $image) }}" alt="Comment Image">
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                                @endif
                                <div class="comment-footer">
                                    <div class="rating" data-komentar-id="{{ $reply->id }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                        <span data-rating="{{ $i }}" class="{{ isset($ratings[$reply->id]) && $ratings[$reply->id]->rating >= $i ? 'selected' : '' }}">★</span>
                                        @endfor
                                    </div>
                                    @if(auth()->check() && (auth()->user()->level == 1 || auth()->user()->id == $reply->id_user))
                                    <div class="delete-button" onclick="deleteComment({{ $reply->id }})">{{ __('post.delete') }}</div>
                                    @else
                                    <div class="delete-button disabled">{{ __('post.delete') }}</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @if(count($komentar_replies[$komentar->id]) > 1)
                            <div class="show-more" id="show-more-{{ $komentar->id }}" onclick="showMoreReplies({{ $komentar->id }})">
                                {{ __('post.show_more') }}
                            </div>
                            @endif
                        </div>
                        @endif
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div id="registerForm" class="register-form mt-5">
        <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data" id="post-form">
            @csrf
            <h1>{{ __('post.buat_postingan_baru') }}</h1>
            <input type="hidden" name="id_mk" value="{{ session('id_matakuliah') }}">
            <textarea class="mb-2 mt-3" name="isi_post" placeholder="{{ __('post.isi_post') }}"></textarea>
            <div class="comment-actions">
                <label for="file-upload" class="file-upload">
                    <input type="file" id="file-upload" name="file_post[]" accept="image/*" multiple style="display: none;" onchange="previewImages(event)">
                    <i class="fa fa-image"></i>
                </label>
                <button type="button" class="btn btn-info text-light" onclick="submitForm()">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
            <div class="image-preview" id="image-preview"></div>
            <button class="buttoncancel mt-3" type="button" onclick="closeRegisterForm()">{{ __('post.batal') }}</button>
        </form>
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

    function previewImages2(event) {
        const id = event.target.id.split('-')[2]; // แยก id ของคอมเม้น
        const files = event.target.files;
        const preview = document.getElementById('image-preview-' + id);

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
            alert('Please write a comment');
            isValid = false;
        }

        // ตรวจสอบขนาดไฟล์
        imageFiles.forEach(file => {
            if (file.size > maxFileSize) {
                alert('File size is too large. Please select a file that is not larger than 2 MB');
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
                    document.getElementById("registerForm").style.display = "none"; // ปิด Popup
                    // Redirect to the same page to refresh
                    loadPosts();
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
            alert('Please write a comment');
            isValid = false;
        }

        // ตรวจสอบขนาดไฟล์
        imageFiles.forEach(file => {
            if (file.size > maxFileSize) {
                alert('File size is too large. Please select a file that is not larger than 2 MB');
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
                    loadComments(id); // เรียกใช้ฟังก์ชัน loadComments เพื่อรีเฟรชคอมเม้นต์
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        }
    }

    function submitForm3(id,post_id) {
        const form = document.getElementById('reply-comment-form-' + id);
        const formData = new FormData(form);
        const commentInput = form.querySelector('textarea[name="isi_komentar"]').value.trim();
        const maxFileSize = 2048 * 1024; // ขนาดไฟล์สูงสุดในหน่วย bytes (2048 KB)
        let isValid = true;

        // ตรวจสอบว่ามีการเขียนคอมเม้นหรือไม่
        if (commentInput === '') {
            alert('Please write a comment');
            isValid = false;
        }

        // ตรวจสอบขนาดไฟล์
        imageFiles.forEach(file => {
            if (file.size > maxFileSize) {
                alert('File size is too large. Please select a file that is not larger than 2 MB');
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
                    loadComments(post_id); // เรียกใช้ฟังก์ชัน loadComments เพื่อรีเฟรชคอมเม้นต์
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

    function bindRatingEvents() {
        document.querySelectorAll('.rating span').forEach(star => {
            star.addEventListener('click', function () {
                let rating = this.getAttribute('data-rating');
                let komentarId = this.parentNode.getAttribute('data-komentar-id');
                let stars = this.parentNode.children;
                for (let i = 0; i < stars.length; i++) {
                    if (i < rating) {
                        stars[i].classList.add('selected');
                    } else {
                        stars[i].classList.remove('selected');
                    }
                }

                fetch('{{ route("submit.rating.post") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_komentar: komentarId,
                        rating: rating
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const avgRatingElement = document.querySelector(`.avg_rating[data-komentar-id="${data.komentar_id}"]`);
                        avgRatingElement.querySelector('span:nth-child(2)').textContent = data.avg_rating;
                        avgRatingElement.querySelector('span:nth-child(3)').textContent = `(${data.rating_count})`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
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
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindRatingEvents();
        loadPosts();
    });

    function sortComments(postId) {
        const sortOption = document.getElementById('sort-comments-' + postId).value;
        fetch(`/komentar-post/sort`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                post_id: postId,
                sort: sortOption
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('comments-container-' + postId).innerHTML = data.commentsHtml;
                bindRatingEvents();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function deleteComment(commentId) {
        if (confirm('คุณต้องการลบคอมเม้นนี้หรือไม่?')) {
            fetch(`/komentar-post/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadComments(data.id_post);
                } else {
                    alert('Error deleting comment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting comment: ' + error.message);
            });
        }
    }

    function deletePost(id) {
        if (confirm('คุณต้องการลบโพสต์นี้หรือไม่?')) {
            fetch(`/post/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPosts();
                } else {
                    //eng alert
                    alert('Error deleting post: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting post: ' + error.message);
            });
        }
    }

    function sortPosts() {
        const sortOption = document.getElementById('sort-posts').value;
        const url = new URL(window.location.href);
        url.searchParams.set('id_mk', {{ session('id_matakuliah') }});
        url.searchParams.set('sort', sortOption);
        url.searchParams.set('ajax', 1);

        fetch(url)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newRightColumn = doc.querySelector('#post-container');
            document.querySelector('#post-container').innerHTML = newRightColumn.innerHTML;
            bindRatingEvents();
        })
        .catch(error => {
            console.error('Error sorting comments:', error);
        });
    }

    function loadPosts() {
        const url = new URL(window.location.href);
        url.searchParams.set('ajax', 1);
        url.searchParams.set('sort', document.getElementById('sort-posts').value);

        fetch(url)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newComments = doc.querySelector('#post-container2');
            document.querySelector('#post-container2').innerHTML = newComments.innerHTML;
            bindRatingEvents();
        })
        .catch(error => {
            console.error('Error loading comments:', error);
        });
    }

    function loadComments(postId) {
        fetch(`/komentar-post/${postId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('comments-container-' + postId).innerHTML = data.commentsHtml;
                bindRatingEvents();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    let currentImageIndex = 0;
    let imageList = [];

    function openImageModal(src, index, images, postId) {
        currentImageIndex = index;
        imageList = images.map(img => "{{ Storage::url('public/komentarPost/') }}" + postId + "/" + img);

        const modal = document.getElementById("imageModal");
        const modalImg = document.getElementById("modalImage");
        modal.style.display = "block";
        modalImg.src = src;
        modalImg.style.maxWidth = "80%";
        modalImg.style.maxHeight = "80vh";
    }

    function openImageModalPost(src, index, images, postId) {
        currentImageIndex = index;
        imageList = images.map(img => "{{ Storage::url('public/post/') }}" + postId + "/" + img);

        const modal = document.getElementById("imageModal");
        const modalImg = document.getElementById("modalImage");
        modal.style.display = "block";
        modalImg.src = src;
        modalImg.style.maxWidth = "80%";
        modalImg.style.maxHeight = "80vh";
    }

    function changeImage(direction) {
        currentImageIndex += direction;
        if (currentImageIndex < 0) {
            currentImageIndex = imageList.length - 1;
        } else if (currentImageIndex >= imageList.length) {
            currentImageIndex = 0;
        }
        const modalImg = document.getElementById("modalImage");
        modalImg.src = imageList[currentImageIndex];
    }

    function closeImageModal() {
        var modal = document.getElementById("imageModal");
        modal.style.display = "none";
    }

    function toggleLove(postId) {
        const loveButton = document.querySelector(`.love-button[data-post-id="${postId}"]`);
        const loveCount = document.getElementById(`love-count-${postId}`);
        const url = `/love/${postId}`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loveButton.classList.toggle('loved');
                loveCount.textContent = data.love_count;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function showMoreReplies(commentId) {
        const repliesContainer = document.getElementById(`replies-container-${commentId}`);
        const showMoreButton = document.getElementById(`show-more-${commentId}`);
        const hiddenReplies = repliesContainer.querySelectorAll('.reply-comment[style*="display: none;"]');
        hiddenReplies.forEach(reply => {
            reply.style.display = 'block';
        });
        showMoreButton.style.display = 'none';
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const postId = urlParams.get('id_post');
        if (postId) {
            const postElement = document.getElementById('post-' + postId);
            if (postElement) {
                postElement.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>

@endsection
