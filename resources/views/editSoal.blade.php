@extends('navbar')
@section('body')
<div class="container mt-3">
  <h2>Daftar Soal</h2>
  
  <div class="d-flex">
    <form action="{{ route('/editsoal')}}" method="post">
        <input type="hidden" name="semester" value="{{session.get('namamk')}}">
      <input type="hidden" name="edit" value="1">
      <button type="submit">Add</button>
    </form>
  <form action="{{ route('/editsoal')}}" method="post">
    <input type="hidden" name="edit" value="2">
    <button type="submit">Edit</button>
  </form>
  <form action="{{ route('/editsoal')}}" method="post">
    <input type="hidden" name="edit" value="3">
    <button type="submit">Hapus</button>
  </form>

</div>

  
  <table class="table table-bordered table-light table-striped my-5">
    <thead class="table-dark">
      <input type="hidden" name="id_semester" value="1">
      <tr>
        <th colspan="5">Mata Kuliah {{session.get('namamk')}}</th>
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
     
      
        
        @each ((soals, index) in soal)
        <tr>
        <form action="{{ route('/soal')}}" method="get">
          <th scope="row">{{ index+1 }}</th>
          <td>{{ soals.nama_soal }}</td>
          <td>{{ soals.nama }}</td>
          <td>{{ soals.updatedAt }}</td>
        </form>  
          <form action="{{ route('/lihatsoal')}}" method="post">
            <input type="hidden" name="soals_id" value="{{ soals.id }}">
            <td><button type="submit" class="btn btn-info text-light">Lihat</button></td>
          </form>
        </tr>
       @end
        
   
      <tr>
        <th colspan="3">Total</th>
        <th>{{sks}}</th>
        <td></td>
      </tr>
  </table>
</div>
@endsection