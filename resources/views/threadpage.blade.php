<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengantar Biologi</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
        .sidebar {
            width: 250px;
            margin-right: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
           
        }
        .courses {
            width: 200px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .search h4, .courses h4 {
            margin-top: 0;
            color: #007bff;
        }
        .search label, .search select, .search button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        .courses ul {
            list-style: none;
            padding: 0;
        }
        .courses ul li {
            margin-bottom: 10px;
        }
        .courses ul li a {
            text-decoration: none;
            color: #007bff;
        }
        .post {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 20px;
        }
        .post:first-child {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }
        .user {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .rating span {
            color: #ffc107;
        }
        .rating a {
            color: #007bff;
            text-decoration: none;
            margin-left: 10px;
        }
        .comment {
            border-top: 1px solid #eee;
            margin-top: 10px;
            padding-top: 10px;
        }
        .button-container {
            flex-grow: 1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        
            background: #fff;
            text-align: center;
          
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border-radius: 4px;
            text-decoration: none;
        }
        .post {
            margin-bottom: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .post-header .user {
            font-weight: bold;
        }
        .post-header .rating {
            color: #ffc107;
        }
        .post-content {
            margin: 10px 0;
            padding: 10px;
            background: #f7f7f7;
            border-radius: 8px;
        }
        .post-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .rating {
            display: flex;
            align-items: center;
        }
        .rating span {
            cursor: pointer;
            font-size: 20px;
            color: #ddd;
        }
        .rating span.selected,
        .rating span:hover,
        .rating span.hover {
            color: #ffc107;
        }
        .comment-button {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .comments {
            margin-top: 20px;
        }
        .comment {
            margin-top: 10px;
            padding: 10px;
            background: #f1f1f1;
            border-radius: 8px;
            margin-left: 50px; /* ชิดขวา */
        }
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .comment-header .user {
            font-weight: bold;
        }
        .comment-header .rating {
            color: #ffc107;
        }
        .comment-content {
            margin: 10px 0;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
        }
        .comment-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .reply-button {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>PENGANTAR BIOLOGI</h2>
    </div>
    <div class="container">
        <div class="sidebar">
            <div class="search">
                <h4>Cari</h4>
                <form>
                    <label>Jenjang</label>
                    <select>
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                    <label>Fakultas</label>
                    <select>
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                    <label>Prodi</label>
                    <select>
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                    <label>Semester</label>
                    <select>
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                    <button type="submit">Cari</button>
                </form>
            </div>
        </div>
        <div class="content">
            <div class="button-container">
                <a href="#" class="button">Buat postingan baru</a>
            </div>
            <div class="post">
            <div class="post-header">
                <div class="user">SUTHICHAI</div>
                <div class="rating">4.0 ★★★★☆</div>
            </div>
            <div class="post-content">
                นี่คือเนื้อหาโพสต์
            </div>
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

        <div class="comments">
            <div class="comment">
                <div class="comment-header">
                    <div class="user">NATTHAWUT</div>
                    <div class="rating">4.0 ★★★★☆</div>
                </div>
                <div class="comment-content">
                    นี่คือเนื้อหาคอมเมนต์
                </div>
                <div class="comment-footer">
                    <div class="rating">
                        <span data-rating="1">★</span>
                        <span data-rating="2">★</span>
                        <span data-rating="3">★</span>
                        <span data-rating="4">★</span>
                        <span data-rating="5">★</span>
                    </div>
                    <div class="reply-button">Reply</div>
                </div>
            </div>
        </div>

            
        </div>
        <div class="courses">
            <h4>Matakuliah</h4>
            <ul>
                <li><a href="#">PENGANTAR BIOLOGI</a></li>
                <li><a href="#">PENGANTAR KIMIA</a></li>
                <li><a href="#">PENGANTAR FISIKA</a></li>
                <li><a href="#">PENGANTAR TIK</a></li>
            </ul>
        </div>
    </div>
</body>
<script>
        document.querySelectorAll('.rating span').forEach(star => {
            star.addEventListener('click', function() {
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
    </script>
</html>
