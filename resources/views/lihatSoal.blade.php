@extends('navbar')
@section('body')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/komentarSoal.css">
    <style>
        .nav-link {
            font-size: 18px;
            /* ขนาดของฟอนต์ */
        }

        .navbar-brand img {
            width: 120px;
            /* ขนาดโลโก้ */
        }
        .delete-button.disabled {
        cursor: not-allowed;
        color: grey;
    }
    </style>
</head>
<div>
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

<div class="rightcolumn" id="rightcolumn">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <form class="mt-4" action="/komentar" method="post" enctype="multipart/form-data" id="comment-form">
        @csrf
        <input type="hidden" name="id_soal" value="{{ $soals->id }}">
        <div class="comment-box">
            <h2>Komentar</h2>
            <textarea class="mb-2 mt-3" name="isi_komentar" placeholder='Isi Komentar...'></textarea>
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
    <select class="sort-comments mt-3 mb-2" id="sort-comments" onchange="sortComments()">
        <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Terbaru</option>
        <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Terlama</option>
        <option value="most_rated" {{ $sort == 'most_rated' ? 'selected' : '' }}>Terbanyak Rating</option>
    </select>
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
                @if ($index < 2) <a href="javascript:void(0)" onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}', {{$index}}, {{ json_encode($images) }})">
                    <img src="{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $image) }}" alt="Comment Image">
                    </a>
                    @endif
                    @endforeach
                    @if ($imageCount > 2)
                    <div class="more-images" onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $komentar->id_soal . '/' . $images[2]) }}', 2, {{ json_encode($images) }})">+{{ $imageCount - 2 }}</div>
                    @endif
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
                <div class="rating" data-komentar-id="{{ $komentar->id }}">
                    @for ($i = 1; $i <= 5; $i++) <span data-rating="{{ $i }}" class="{{ isset($ratings[$komentar->id]) && $ratings[$komentar->id]->rating >= $i ? 'selected' : '' }}">★</span>
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
                <form action="/komentar" method="post" enctype="multipart/form-data" class="reply-comment-form" id="reply-comment-form-{{ $komentar->id }}">
                    @csrf
                    <input type="hidden" name="id_soal" value="{{ $soals->id }}">
                    <input type="hidden" name="parent_id" value="{{ $komentar->id }}">
                    <div class="comment-box">
                        <textarea class="mb-2 mt-3" name="isi_komentar" placeholder='Isi Komentar...'></textarea>
                        <div class="comment-actions">
                            <label for="file-upload-{{ $komentar->id }}" class="file-upload">
                                <input type="file" id="file-upload-{{ $komentar->id }}" name="file_komentar[]" accept="image/*" multiple style="display: none;" onchange="previewImages2(event)">
                                <i class="fa fa-image"></i>
                            </label>
                            <button type="button" class="btn btn-info text-light" onclick="submitForm2({{ $komentar->id }})">
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
                    @if ($index < 2) <a href="javascript:void(0)" onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $image) }}', {{$index}}, {{ json_encode($images) }})">
                        <img src="{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $image) }}" alt="Comment Image">
                        </a>
                        @endif
                        @endforeach
                        @if ($imageCount > 2)
                        <div class="more-images" onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $images[2]) }}', 2, {{ json_encode($images) }})">+{{ $imageCount - 2 }}</div>
                        @endif
                        @foreach ($images as $index => $image)
                        @if ($index >= 2)
                        <a href="javascript:void(0)" onclick="openImageModal('{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $image) }}', {{$index}}, {{ json_encode($images) }})" style="display:none;">
                            <img src="{{ Storage::url('public/komentarSoal/' . $reply->id_soal . '/' . $image) }}" alt="Comment Image">
                        </a>
                        @endif
                        @endforeach
                </div>
                @endif
                <div class="comment-footer">
                    <div class="rating" data-komentar-id="{{ $reply->id }}">
                        @for ($i = 1; $i <= 5; $i++) <span data-rating="{{ $i }}" class="{{ isset($ratings[$reply->id]) && $ratings[$reply->id]->rating >= $i ? 'selected' : '' }}">★</span>
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

<!-- The Modal -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeImageModal()">&times;</span>
    <span class="prev" onclick="changeImage(-1)">&#10094;</span>
    <span class="next" onclick="changeImage(1)">&#10095;</span>
    <div class="modal-content-wrapper">
        <img class="modal-content" id="modalImage">
    </div>
</div>
</div>
<script>
    let imageFiles = [];

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

    function previewImages2(event) {
        const id = event.target.id.split('-')[2]; // แยก id ของคอมเม้น
        const files = event.target.files;
        const preview = document.getElementById('image-preview-' + id);

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

    function loadComments() {
        const url = new URL(window.location.href);
        url.searchParams.set('ajax', 1);
        url.searchParams.set('sort', document.getElementById('sort-comments').value);

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newComments = doc.querySelector('#comments-container');
                document.querySelector('#comments-container').innerHTML = newComments.innerHTML;

                // Re-bind rating events
                bindRatingEvents();
            })
            .catch(error => {
                console.error('Error loading comments:', error);
            });
    }

    function openImageModal(src, index, images) {
        currentImageIndex = index;
        imageList = images.map(img => "{{ Storage::url('public/komentarSoal/' . $soals->id . '/') }}" + img);

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

    function showReplyForm(komentarId) {
        document.querySelectorAll('.reply-form').forEach(form => form.style.display = 'none');
        document.getElementById('reply-form-' + komentarId).style.display = 'block';
    }

    function bindRatingEvents() {
        document.querySelectorAll('.rating span').forEach(star => {
            star.addEventListener('click', function() {
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

                fetch('{{ route("submit.rating") }}', {
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

                            // รีเฟรช rightcolumn
                            loadComments();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            star.addEventListener('mouseover', function() {
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

            star.addEventListener('mouseout', function() {
                let stars = this.parentNode.children;
                for (let i = 0; i < stars.length; i++) {
                    stars[i].classList.remove('hover');
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        bindRatingEvents();
    });

    function sortComments() {
        const sortOption = document.getElementById('sort-comments').value;
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortOption);
        url.searchParams.set('ajax', 1);

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newRightColumn = doc.querySelector('#rightcolumn');
                document.querySelector('#rightcolumn').innerHTML = newRightColumn.innerHTML;
                bindRatingEvents();
            })
            .catch(error => {
                console.error('Error sorting comments:', error);
            });
    }

    function deleteComment(commentId) {
        if (confirm('คุณต้องการลบคอมเม้นนี้หรือไม่?')) {
            fetch(`/komentar/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        loadComments();
                    } else {
                        alert('เกิดข้อผิดพลาดในการลบคอมเม้น: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการลบคอมเม้น: ' + error.message);
                });
        }
    }
</script>
@endsection