@extends('navbar')
@section('body')
<div class="container mt-3">
  <h2>Daftar Soal</h2>

  <table class="table table-bordered table-light table-striped my-5">
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
          <td>{{ $soals->updated_at }}</td>
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