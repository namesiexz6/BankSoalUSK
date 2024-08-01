@extends('navbar')
@section('body')
<div class="background"
  style="background-image: url('{{ Storage::url('pretty-women-studying-bed.jpg') }}'); background-size: cover; background-position: top; height: 20vh; display: flex; align-items: center; justify-content: center;">
  <h2 style="color: white; text-align: center; margin-bottom: 25px; margin-top: 28px;">Mata Kuliah {{session('namamk')}}
  </h2>
</div>
<div class="container mt-3">

  <h2 class="mb-3">Daftar Soal</h2>
  <form action="{{ route('post.index')}}" method="post">
    @csrf
    <input type="hidden" name="id_mk" value="{{session('id_matakuliah')}}">
    <button type="submit" class="btn btn-info text-light">Thread</button>
  </form>
  <table class="table table-bordered table-light table-striped">
    <thead class="table-dark">
      <input type="hidden" name="id_semester" value="1">
      <tr>
        <th colspan="5">Mata Kuliah {{session('namamk')}}</th>
      </tr>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Nama Soal</th>
        <th scope="col">Dibuat Oleh</th>
        <th scope="col">Update</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>


      @foreach ($soal as $soals)
      <tr>
      <th scope="row">{{ $loop->iteration }}</th>
      <td>{{ $soals->nama_soal }}</td>
      <td>{{ $soals->nama }}</td>
      <td>{{ \Carbon\Carbon::parse($soals->updated_at)->locale('id')->diffForHumans() }}</td>
      <form action="{{ route('pilihHsoal')}}" method="post">
        @csrf
        <input type="hidden" name="soals_id" value="{{ $soals->id }}">
        <td><button type="submit" class="btn btn-info text-light">Lihat</button></td>
      </form>
      </tr>
    @endforeach



    </tbody>
  </table>

</div>
@endsection