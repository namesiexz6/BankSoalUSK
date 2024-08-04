@if(count($prodi) > 0)
    <h2 class="mt-3">Daftar Prodi</h2>
    <table class="table table-bordered table-light table-striped my-3">
        <thead class="table-dark">
            <input type="hidden" name="id_fakultas" value="1">
            <tr>
                <th colspan="5">Fakultas {{ session('fakultas_nama') }}</th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Prodi</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prodi as $p)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $p->nama }}</td>
                    <td>
                        <div class="d-flex">
                            <button type="submit" name="edit" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;" onclick="openRegisterFormEdit('{{ $p->id }}','{{ $p->nama }}','{{ $p->id_fakultas }}')">Edit</button>
                            <form action="{{ route('prodiM')}}" method="post" onsubmit="return confirmDelete()">
                                @csrf
                                <input type="hidden" name="prodi_id" value="{{ $p->id }}">
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
