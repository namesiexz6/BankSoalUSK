<h2 class="mt-3">{{ __('management.daftar_soal') }}</h2>
<table class="table table-bordered table-light table-striped my-3">
    <thead class="table-dark">
        <tr>
            <th colspan="5">{{ __('management.mata_kuliah') }} {{ session('matakuliah_nama') }}</th>
        </tr>
        <tr>
            <th scope="col">{{ __('management.no') }}</th>
            <th scope="col">{{ __('management.nama_soal') }}</th>
            <th scope="col">{{ __('management.dibuat_oleh') }}</th>
            <th scope="col">{{ __('management.update') }}</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($soal as $soals)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $soals->nama_soal }}</td>
            <td>{{ $soals->user->nama }}</td>
            <td>{{ $soals->updated_at }}</td>
            <td>
                <div class="d-flex">
                    <form action="{{ route('soalM') }}" method="post" onsubmit="return confirmDelete()">
                        @csrf
                        <input type="hidden" name="soal_id" value="{{ $soals->id }}">
                        <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">{{ __('management.hapus') }}</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
