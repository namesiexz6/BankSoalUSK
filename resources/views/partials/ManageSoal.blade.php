<h2 class="mt-3">Daftar Soal</h2>
<table class="table table-bordered table-light table-striped my-3">
    <thead class="table-dark">
        <tr>
            <th colspan="5">Mata Kuliah {{ session('matakuliah_nama') }}</th>
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
            <td>
                <div class="d-flex">
                    <form action="{{ route('soalM') }}" method="post" onsubmit="return confirmDelete()">
                        @csrf
                        <input type="hidden" name="soal_id" value="{{ $soals->id }}">
                        <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
