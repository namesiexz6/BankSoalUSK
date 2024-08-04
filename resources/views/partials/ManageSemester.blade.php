@if(count($semester) > 0)
    <h2 class="mt-3">Daftar Semester</h2>
    <table class="table table-bordered table-light table-striped my-3">
        <thead class="table-dark">
            <tr>
                <th colspan="5">Prodi {{ session('prodi_nama') }}</th>
            </tr>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Semester</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($semester as $s)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $s->nama }}</td>
                    <td>
                        <div class="d-flex">
                            <button type="button" class="btn btn-info text-light" style="margin-right: 1ch; background-color: blue;" onclick="openRegisterFormEdit('{{ $s->id }}','{{ $s->nama }}','{{ $s->id_prodi }}')">Edit</button>
                            <form action="{{ route('semesterM')}}" method="post" onsubmit="return confirmDelete()">
                                @csrf
                                <input type="hidden" name="semester_id" value="{{ $s->id }}">
                                <button type="submit" name="edit" value="2" class="btn btn-info text-light" style="background-color: red;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
