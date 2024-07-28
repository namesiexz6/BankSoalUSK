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
            <div class="rating" data-komentar-id="{{ $komentar->id }}">
                @for ($i = 1; $i <= 5; $i++) <span data-rating="{{ $i }}" class="{{ isset($ratings[$komentar->id]) && $ratings[$komentar->id]->rating >= $i ? 'selected' : '' }}">★</span>
                    @endfor
            </div>
            <div class="comment-button" onclick="showReplyForm({{ $komentar->id }})">Balas</div>
        </div>
        <!-- ฟอร์มตอบกลับ -->
        <div class="reply-form" id="reply-form-{{ $komentar->id }}">
            <form action="/komentar" method="post" enctype="multipart/form-data" class="reply-comment-form" id="reply-comment-form">
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
                        <button type="button" class="btn btn-info text-light" onclick="submitForm2()">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                    <div class="image-preview" id="image-preview2"></div>
                </div>
            </form>
        </div>
    </div>
    <!-- Replies -->
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
                @if($user->get($komentar->id_user)->level == 1)
                Admin
                @elseif($user->get($komentar->id_user)->level == 2)
                Dosen
                @elseif($user->get($komentar->id_user)->level == 3)
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

                    <!-- Hidden images for Lightbox -->
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

            </div>
        </div>
        @endforeach
    </div>
    @endif

    @endforeach
</div>