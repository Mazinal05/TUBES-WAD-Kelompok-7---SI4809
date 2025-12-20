@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Kelola Data UMKM</h3>
    <a href="{{ route('admin.umkms.create') }}" class="btn btn-primary">+ Tambah UMKM</a>
</div>

<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama UMKM</th>
                    <th>Kategori</th>
                    <th>WhatsApp</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($umkms as $index => $u)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $u->nama_umkm }}</td>
                    <td>{{ $u->kategori }}</td>
                    <td>{{ $u->no_whatsapp }}</td>
                    <td>
                        <a href="{{ route('admin.umkms.edit', $u->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.umkms.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data UMKM.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if(method_exists($umkms, 'links'))
            {{ $umkms->links() }}
        @endif
    </div>
</div>
@endsection