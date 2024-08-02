@foreach ($posts as $post)
    <div class="post" id="post-{{ $post->id }}">
        <div class="post-header">
            <div class="user">{{ $userPost->get($post->id_user)->nama }}</div>

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
                    @if ($index < 2) <a href="javascript:void(0)"
                            onclick="openImageModalPost('{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ session('id_matakuliah') }})">
                            <img src="{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}" alt="Comment Image">
                        </a>
                    @endif

                @endforeach
                @if ($imageCount > 2)
                    <div class="more-image-post"
                        onclick="openImageModalPost('{{ Storage::url('public/post/' . $post->id_mk . '/' . $images[2]) }}', 2, {{ json_encode($images) }}, {{ session('id_matakuliah') }})">
                        +{{ $imageCount - 2 }}
                    </div>
                @endif

                @foreach ($images as $index => $image)
                    @if ($index >= 2)
                        <a href="javascript:void(0)"
                            onclick="openImageModalPost('{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}', {{$index}}, {{ json_encode($images) }},{{ session('id_matakuliah') }})"
                            style="display:none;">
                            <img src="{{ Storage::url('public/post/' . $post->id_mk . '/' . $image) }}" alt="Comment Image">
                        </a>
                    @endif

                @endforeach
            </div>
        @endif

        <div class="post-footer">
            <div class="love">
                <button class="love-button" data-post-id="{{ $post->id }}" onclick="toggleLove({{ $post->id }})">
                    <i class="fa fa-heart"></i>
                </button>
                <span class="love-count" id="love-count-{{ $post->id }}">
                    {{ $post->loves->count() }}
                </span>
            </div>
            <div class="comment-button" onclick="showCommentForm({{ $post->id }})">Komentar</div>
            @if(auth()->check() && (auth()->user()->level == 1 || auth()->user()->id == $post->id_user))
                <div class="delete-button" onclick="deletePost({{ $post->id }})">Delete</div>
            @else

                <div class="delete-button disabled">Delete</div>
            @endif

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
                            <input type="file" id="file-upload-{{ $post->id }}" name="file_komentar[]" accept="image/*"
                                multiple style="display: none;" onchange="previewImages2(event)">
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

        <div id="comments-container-{{ $post->id }}" class="comments-container">
            @foreach ($komentar_parents as $komentar)

                @if ($komentar->id_post == $post->id)

                    <select class="sort-comments mt-3 mb-2" id="sort-comments-{{ $post->id }}"
                        onchange="sortComments({{ $post->id }})">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="most_rated">Terbanyak Rating</option>
                    </select>

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
                                            onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $komentar->id_post }})">
                                            <img src="{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $image) }}"
                                                alt="Comment Image">
                                        </a>
                                    @endif

                                @endforeach
                                @if ($imageCount > 2)
                                    <div class="more-images"
                                        onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $images[2]) }}', 2, {{ json_encode($images) }}, {{ $komentar->id_post }})">
                                        +{{ $imageCount - 2 }}
                                    </div>
                                @endif

                                @foreach ($images as $index => $image)
                                    @if ($index >= 2)
                                        <a href="javascript:void(0)"
                                            onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $komentar->id_post }})"
                                            style="display:none;">
                                            <img src="{{ Storage::url('public/komentarPost/' . $komentar->id_post . '/' . $image) }}"
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
                            @if(auth()->check() && (auth()->user()->level == 1 || auth()->user()->id == $komentar->id_user))
                                <div class="delete-button" onclick="deleteComment({{ $komentar->id }})">Delete</div>
                            @else

                                <div class="delete-button disabled">Delete</div>
                            @endif

                        </div>

                        <div class="reply-form" id="reply-form-{{ $komentar->id }}">
                            <form action="/komentar-post" method="post" enctype="multipart/form-data" class="reply-comment-form"
                                id="reply-comment-form-{{ $post->id }}">
                                @csrf
                                <input type="hidden" name="id_post" value="{{ $komentar->id_post }}">
                                <input type="hidden" name="parent_id" value="{{ $komentar->id }}">
                                <div class="comment-box">
                                    <textarea class="mb-2 mt-3" name="isi_komentar" placeholder='Isi Komentar...'></textarea>
                                    <div class="comment-actions">
                                        <label for="file-upload-{{ $komentar->id }}" class="file-upload">
                                            <input type="file" id="file-upload-{{ $komentar->id }}" name="file_komentar[]"
                                                accept="image/*" multiple style="display: none;" onchange="previewImages2(event)">
                                            <i class="fa fa-image"></i>
                                        </label>
                                        <button type="button" class="btn btn-info text-light"
                                            onclick="submitForm3({{ $post->id }})">
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
                                <div class="reply-comment">
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
                                                        onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $reply->id_post }})">
                                                        <img src="{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $image) }}"
                                                            alt="Comment Image">
                                                    </a>
                                                @endif

                                            @endforeach
                                            @if ($imageCount > 2)
                                                <div class="more-images"
                                                    onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $images[2]) }}', 2, {{ json_encode($images) }}, {{ $reply->id_post }})">
                                                    +{{ $imageCount - 2 }}
                                                </div>
                                            @endif

                                            @foreach ($images as $index => $image)
                                                @if ($index >= 2)
                                                    <a href="javascript:void(0)"
                                                        onclick="openImageModal('{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $image) }}', {{$index}}, {{ json_encode($images) }}, {{ $reply->id_post }})"
                                                        style="display:none;">
                                                        <img src="{{ Storage::url('public/komentarPost/' . $reply->id_post . '/' . $image) }}"
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
                                        @if(auth()->check() && (auth()->user()->level == 1 || auth()->user()->id == $reply->id_user))
                                            <div class="delete-button" onclick="deleteComment({{ $reply->id }})">Delete</div>
                                        @else

                                            <div class="delete-button disabled">Delete</div>
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                @endif

            @endforeach

        </div>
    </div>
@endforeach
