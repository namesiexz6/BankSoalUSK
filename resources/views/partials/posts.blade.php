@foreach ($posts as $post)
    <div class="post" id="post-{{ $post->id }}">
        <div class="post-header">
            <div class="user">{{ $userPost->get($post->id_user)->nama }}</div>
            <div class="lovecount">♥</div>
        </div>
        <div class="user-level">
            @if($userPost->get($post->id_user)->level == 1)
                Admin
            @elseif($userPost->get($post->id_user)->level == 2)
                Dosen
            @elseif($userPost->get($post->id_user)->level == 3)
                Mahasiswa
            @endif
            <span>{{ \Carbon\Carbon::parse($post->updated_at)->locale('id')->diffForHumans() }}</span>
        </div>
        <div class="post-content">
            {{ $post->isi_post }}
        </div>
        @if ($post->file_post)
            <div class="post-images">
                @php
                    $images = json_decode($post->file_post);
                    $imageCount = count($images);
                @endphp
                @foreach ($images as $index => $image)
                    @if ($index < 2)
                        <a href="javascript:void(0)"
                            onclick="openImageModal('{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $post->id }})">
                            <img src="{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}" alt="Comment Image">
                        </a>
                    @endif
                @endforeach
                @if ($imageCount > 2)
                    <div class="more-image-post"
                        onclick="openImageModal('{{ Storage::url('public/post/' . $post->id_mk . '/' . $images[2]) }}', 2, {{ json_encode($images) }}, {{ $post->id }})">
                        +{{ $imageCount - 2 }}
                    </div>
                @endif
                @foreach ($images as $index => $image)
                    @if ($index >= 2)
                        <a href="javascript:void(0)"
                            onclick="openImageModal('{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $post->id }})"
                            style="display:none;">
                            <img src="{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}" alt="Comment Image">
                        </a>
                    @endif
                @endforeach
            </div>
        @endif
        <div class="post-footer">
            <div class="love">
                <span data-rating="1">♥</span>
            </div>
            <div class="comment-button" onclick="showCommentForm({{ $post->id }})">Komentar</div>
        </div>

        <div class="comment-form" id="comment-form-{{ $post->id }}">
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
        <select class="sort-comments mt-3 mb-2" id="sort-comments-{{ $post->id }}"
            onchange="sortComments({{ $post->id }})">
            <option value="newest">Terbaru</option>
            <option value="oldest">Terlama</option>
            <option value="most_rated">Terbanyak Rating</option>
        </select>
        <div id="comments-container-{{ $post->id }}" class="comments-container">
            <!-- AJAX loaded comments will go here -->
        </div>
    </div>
@endforeach
