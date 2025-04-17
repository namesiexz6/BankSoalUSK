<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    .nav-link {
        font-size: 18px;
        /* ขนาดของฟอนต์ */
    }

    .navbar-brand img {
        width: 120px;
        /* ขนาดโลโก้ */
    }

    .notification-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 300px;
        max-height: 4.5em;
        /* ความสูงสูงสุด 3 บรรทัด */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        line-height: 1.5em;
        /* ความสูงของแต่ละบรรทัด */
        margin-bottom: 0.5em;
    }

    .notification-item a {
        flex-grow: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .notification-delete {
        color: red;
        cursor: pointer;
        margin-left: 10px;
    }
    </style>
    <title>BankSoal</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <nav class="navbar shadow sticky-top navbar-expand-lg navbar-light bg-white ps-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('logo.png') }}" alt="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 ms-4 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-bold" style="color: #134F5C" aria-current="page"
                            href="/">{{ __('navbar.beranda') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" style="color: #134F5C" href="/search">{{ __('navbar.soal') }}</a>
                    </li>
                    @if(auth()->check() && auth()->user()->level == 1)
                    <li class="nav-item">
                        <a class="nav-link fw-bold" style="color: #134F5C"
                            href="/management">{{ __('navbar.management') }}</a>
                    </li>
                    @endif
                </ul>

                <ul class="navbar-nav ms-auto">


                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <span class="badge bg-danger"
                                id="unreadNotificationsCount">{{ $unreadNotificationsCount }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                            @foreach ($notifications as $notification)
                            @php
                            $data = json_decode($notification->data, true);
                            @endphp
                            <li class="notification-item">
                                <a class="dropdown-item" href="{{$data['url']}}">{{ $data['message'] }}</a>
                                <button class="btn btn-link notification-delete"
                                    data-id="{{ $notification->id }}">&times;</button>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endauth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            @if (App::getLocale() == 'en')
                            <i class="flag-icon flag-icon-gb"></i> <!-- US Flag for English -->
                            @elseif (App::getLocale() == 'id')
                            <i class="flag-icon flag-icon-id"></i> <!-- Indonesia Flag for Indonesian -->
                            @endif
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ url('/lang/en') }}">
                                    <i class="flag-icon flag-icon-gb"></i> English
                                    <!-- English -->
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/lang/id') }}">
                                    <i class="flag-icon flag-icon-id"></i> Indonesian
                                    <!-- Indonesian -->
                                </a>
                            </li>
                        </ul>
                    </li>
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->nama }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form action="/logout" method="post">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i
                                            class="bi bi-box-arrow-in-left"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link btn text-light" href="/login"
                                style="background-color: #134F5C">{{ __('navbar.login') }}</a>
                        </li>
                    </ul>
                    @endauth

                    <!-- Language Selector with Flag Icons -->

                </ul>
            </div>
        </div>
    </nav>



    @yield('body')
    <footer class="bg-white text-center text-lg-start mt-auto">
        <div class="text-center p-3" style="background-color: #134F5C; color: white;">
            © 2024 BankSoal Universitas Syiah Kuala
        </div>
    </footer>

    <script>
    $(document).ready(function() {
        $(document).on('click', '.notification-delete', function(event) {
            event.preventDefault();
            event.stopPropagation();

            var notificationId = $(this).data('id');
            var $notificationItem = $(this).closest('.notification-item');
            var $unreadNotificationsCount = $('#unreadNotificationsCount');

            $.ajax({
                url: '/notifications/' + notificationId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    console.log('Delete successful', result);
                    $notificationItem.remove();
                    var currentCount = parseInt($unreadNotificationsCount.text());
                    if (!isNaN(currentCount) && currentCount > 0) {
                        $unreadNotificationsCount.text(currentCount - 1);
                    }
                },
                error: function(err) {
                    console.log('Error:', err);
                    alert('Error deleting notification: ' + (err.responseJSON ? err
                        .responseJSON.message : 'Unknown error'));
                }
            });
        });
    });
    </script>

</body>

</html>