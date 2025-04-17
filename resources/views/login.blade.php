@extends('navbar')

@section('body')
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.88.1">
  <title>Modals · Bootstrap v5.1</title>


  <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/modals/">

  <link rel="stylesheet" href="/css/template.css">

  <!-- Bootstrap core CSS -->
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    .rounded-5 {
      border-radius: .75rem;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>


  <!-- Custom styles for this template -->
  <link href="template.css" rel="stylesheet">
</head>


<body background="https://wallpaperset.com/w/full/1/b/9/7685.jpg" ;>
  <div>
    <br><br><br><br><br>
    <div class="modal-dialog" role="document">
      @if(session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{session('success')}}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
      @if(session()->has('invalidlogin'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{session('invalidlogin')}}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
      <div class="modal-content rounded-5 shadow">
        <div class="modal-body p-5 pt-0">
          <main class="form-signin">
            <br>
            <h1 class="h3 mb-3 fw-normal text-center">LOGIN</h1></br>
            <form action="/login" method="post">
              @csrf
              <div class="form-floating">
                <input type="username" name="username" class="form-control @error('username') is-invalid @enderror"
                  id="username" placeholder="name@example.com" required value="{{old('username')}}" autofocus required>
                <label for="username">NIM/NIP</label>
                @error('username')
          <div class="invalid-feedback">
            {{$message}}
          </div>
        @enderror
              </div>
              <div class="form-floating mt-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                  id="password" placeholder="Password" required value="{{old('password')}}" autofocus required>
                <label for="password">Password</label>
                @error('password')
          <div class="invalid-feedback">
            {{$message}}
          </div>
        @enderror
              </div>


              <button class="w-100 btn btn-lg btn-primary mt-2" type="submit">Login</button>
            </form>
          </main>
        </div>
      </div>
    </div>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
  </div>
</body>

</html>
@endsection