
@if(count($matakuliah) > 0)
    <h2 class="mt-3">Daftar Mata Kuliah</h2>
    <table class="table table-bordered table-light table-striped my-3">
        <thead class="table-dark">
            <input type="hidden" name="id_semester" value="1">
            <tr>
                <th colspan="5">{{ session('semester_nama') }}</th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode</th>
                <th scope="col">Mata Kuliah</th>
                <th scope="col">SKS</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matakuliah as $matakuliahs)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $matakuliahs->kode }}</td>
                    <td>{{ $matakuliahs->nama }}</td>
                    <td>{{ $matakuliahs->sks }}</td>
                    <td>
                        <div class="d-flex">
                            <button type="button" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;" onclick="openRegisterFormEdit('{{ $matakuliahs->id }}','{{ $matakuliahs->kode }}','{{ $matakuliahs->nama }}','{{ $matakuliahs->sks }}','{{ $matakuliahs->id_semester }}')">Edit</button>
                            <form action="{{ route('matakuliahM')}}" method="post" onsubmit="return confirmDelete()">
                                @csrf
                                <input type="hidden" name="matakuliah_id" value="{{ $matakuliahs->id }}">
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
