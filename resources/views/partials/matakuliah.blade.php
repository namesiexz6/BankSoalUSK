@if(count($matakuliah) > 0)
    <h2 class="mt-3">Daftar Mata Kuliah</h2>
    <table class="table table-bordered table-light table-striped my-3">
        <thead class="table-dark">
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
                        <a href="{{ route('tamplikansoal', ['matakuliah_id' => $matakuliahs->id]) }}" class="btn btn-info text-light" style="background-color: #134F5C;">Lihat</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>Tidak ada mata kuliah yang tersedia.</p>
@endif
