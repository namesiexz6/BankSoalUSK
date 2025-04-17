@if(count($matakuliah) > 0)
    <h2 class="mt-3">{{ __('soal.daftar_mata_kuliah') }}</h2>
    <table class="table table-bordered table-light table-striped my-3">
        <thead class="table-dark">
            <tr>
                <th colspan="5">{{ session('semester_nama') }}</th>
            </tr>
            <tr>
                <th scope="col">{{ __('soal.no') }}</th>
                <th scope="col">{{ __('soal.kode') }}</th>
                <th scope="col">{{ __('soal.mata_kuliah') }}</th>
                <th scope="col">{{ __('soal.sks') }}</th>
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
                        <a href="{{ route('tamplikansoal', ['matakuliah_id' => $matakuliahs->id]) }}" class="btn btn-info text-light" style="background-color: #134F5C;">{{ __('soal.lihat') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>{{ __('soal.tidak_ada_mata_kuliah') }}</p>
@endif
