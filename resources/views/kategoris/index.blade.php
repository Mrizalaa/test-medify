@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Kategori Items</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('kategoris.index') }}" class="row mb-3">
        <div class="col-md-3">
            <input type="text" name="kode" value="{{ $kode }}" class="form-control" placeholder="Filter Kode">
        </div>
        <div class="col-md-3">
            <input type="text" name="nama" value="{{ $nama }}" class="form-control" placeholder="Filter Nama">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a href="{{ route('kategoris.index') }}" class="btn btn-secondary">Reset</a>
        </div>
        <div class="col-md-3 text-end">
            <a href="{{ route('kategoris.create') }}" class="btn btn-success">+ Kategori Baru</a>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($kategoris as $kategori)
            <tr>
                <td>{{ $kategori->kode }}</td>
                <td>{{ $kategori->nama }}</td>
                <td>
                    <a href="{{ route('kategoris.show', $kategori) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('kategoris.edit', $kategori) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('kategoris.destroy', $kategori) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3">Tidak ada data</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $kategoris->withQueryString()->links() }}
</div>
@endsection