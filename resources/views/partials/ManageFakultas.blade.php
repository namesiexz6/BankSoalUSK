@if(count($fakultas) > 0)
    <h2 class="mt-3">Daftar Fakultas</h2>
    <table class="table table-bordered table-light table-striped my-3">
        <thead class="table-dark">
            <input type="hidden" name="id_fakultas" value="1">
            <tr>
                <th colspan="5">Jenjang {{ session('jenjang_nama') }}</th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Fakultas</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fakultas as $f)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $f->nama }}</td>
                    <td>
                        <div class="d-flex">
                            <button type="submit" name="edit" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;" onclick="openRegisterFormEdit('{{ $f->id }}','{{ $f->nama }}','{{ $f->id_jenjang }}')">Edit</button>
                            <form action="{{ route('fakultasM')}}" method="post" onsubmit="return confirmDelete()">
                                @csrf
                                <input type="hidden" name="fakultas_id" value="{{ $f->id }}">
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
