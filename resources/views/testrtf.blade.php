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
                    document.getElementById("registerForm").style.display = "none"; // ปิด Popup
                    loadPosts(); // เรียกใช้ฟังก์ชัน loadPosts เพื่อรีเฟรชโพสต์
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
                    loadComments(id); // เรียกใช้ฟังก์ชัน loadComments เพื่อรีเฟรชคอมเม้นต์
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
                    loadComments(data.post_id); // เรียกใช้ฟังก์ชัน loadComments เพื่อรีเฟรชคอมเม้นต์
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
                    loadComments(data.post_id);
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

    function loadPosts() {
        fetch(`/thread`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const postsWrapper = document.getElementById('posts-wrapper');
                postsWrapper.innerHTML = data.postsHtml;
                bindRatingEvents();
                data.posts.forEach(post => {
                    loadComments(post.id); // โหลดคอมเม้นต์สำหรับแต่ละโพสต์
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
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
</script>