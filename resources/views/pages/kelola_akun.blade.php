@extends('templates.app')

@section('content-dinamis')
    <div class="container mt-5">
        <div class="d-flex justify-content-end">
            <form class="d-flex me-3" action="{{ route('kelola_akun.data') }}" method="GET">
                <input type="text" name="cari" placeholder="Cari Akun..." class="form-control me-2">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
            <a href="{{ route('kelola_akun.tambah') }}" class="btn btn-secondary me-2">Tambah Akun</a>
            <a href="{{ route('kelola_akun.export-excel') }}" class="btn btn-success">Export Data (excel)</a>
        </div>

        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        <table class="table table-stripped table-bordered mt-3 text-center">
            <thead>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @if (count($users) < 0)
                    <tr>
                        <td colspan="6">Data Akun Kosong</td>
                    </tr>
                @else
                    @foreach ($users as $index => $item)
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perpage() + ($index + 1) }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['email'] }}</td>
                            <td>{{ $item['role'] }}</td>
                            <td class="d-flex justify-content-center">
                                <a href="{{ route('kelola_akun.ubah', $item['id']) }}" class="btn btn-primary me-2">Edit</a>
                                <button class="btn btn-danger" onclick="showModalDelete('{{$item->id}}','{{$item->name}}')">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        {{-- memanggil pagination --}}
        <div class="d-flex justify-content-end my-3">
            {{ $users->links() }}
        </div>

        {{-- modal delete--}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Akun</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus data akun? <b id="nama_akun"></b>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        function showModalDelete(id, name) {
            $('#nama_akun').text(name);
            let url = "{{ route('kelola_akun.hapus', ':id') }}";
            url = url.replace(':id', id);
            $("form").attr('action', url);
            $('#exampleModal').modal('show');
            $('#id').val(id);
        }
    </script>
@endpush
