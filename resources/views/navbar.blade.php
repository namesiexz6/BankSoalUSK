<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>BankSoal</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <nav class="navbar shadow sticky-top nav-underline navbar-expand-lg navbar-light ps-3" style="background-color: #A3C7E9">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="assets/img/logo.png" width="100" height="30" alt="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 ms-4 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-bold" style="color: #134F5C" aria-current="page" href="/">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-bold" style="color: #134F5C" href="/search">Soal</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-bold" style="color: #134F5C" href="/management">Manajemen</a>
                    </li>

                </ul>
                <ul class="navbar-nav ms-auto ">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{auth()->user()->nama}}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/home"><i class="bi bi-house-door"></i>Home</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="/logout" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-in-left"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
                
                @else

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn text-light" href="/login" style="background-color: #134F5C">Login</a>
                    </li>
                </ul>

                @endauth
                </ul>



            </div>
        </div>
    </nav>

    <div>
        @yield('body')
    </div>

</body>

</html>