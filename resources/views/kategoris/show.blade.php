@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detail Kategori</h1>

    <div class="mb-3">
        <a href="{{ route('kategoris.print', $kategori) }}" class="btn btn-sm btn-secondary">
            Download PDF
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Kode:</strong> {{ $kategori->kode }}</p>
            <p><strong>Nama:</strong> {{ $kategori->nama }}</p>
        </div>
    </div>

    <h3>Items dalam kategori ini</h3>

    @if($kategori->masterItems->isEmpty())
        <p class="text-muted">Belum ada item yang memiliki kategori ini.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>Jenis</th>
                    <th>Harga Beli</th>
                    <th>Supplier</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
            @foreach($kategori->masterItems as $item)
                <tr>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>{{ $item->harga_beli }}</td>
                    <td>{{ $item->supplier }}</td>
                    <td>
                        <a href="{{ url('master-items/view/'.$item->kode) }}" class="btn btn-sm btn-primary">
                            View Item
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('kategoris.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
