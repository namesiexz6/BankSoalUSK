@extends('navbar')

@section('body')


<div class="card my-5">
    <div class="card-body">
        <div class="container">
            <h3 class="card-title">Register for test this aplication</h3>
            <hr>
            <form action="{{ route('register')}}" method="post" enctype="multipart/form-data">
            @csrf
                <label for="nama" class="form-label mt-3">Name:</label>
                <input class="form-control" name="nama" id="nama" type="text"  aria-label="default input">

                <label for="username" class="form-label mt-3">Username:</label>
                <input class="form-control" name="username" id="username" type="text" placeholder="ex : Latihan 1" aria-label="default input">

                <label for="password" class="form-label  mt-3">Password:</label>
                <input class="form-control" type="text" name="password" id="password">

                <label for="level" class="form-label mt-3">Level:</label>
                <select class="form-select" name="level" id="level" aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <option value="1">Admin</option>
                    <option value="2">Dosen</option>
                    <option value="3">Mahasiswa</option>
                </select>

                <button type="submit" class="btn btn-info text-light mt-3">submit</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection