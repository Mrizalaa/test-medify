@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $method == 'create' ? 'Tambah Kategori' : 'Edit Kategori' }}</h1>

    <form method="POST" action="{{ $method == 'create' ? route('kategoris.store') : route('kategoris.update', $kategori) }}">
        @csrf
        @if($method == 'edit')
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" name="kode" id="kode" class="form-control" 
                   value="{{ old('kode', $kategori->kode) }}" required>
            @error('kode')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" 
                   value="{{ old('nama', $kategori->nama) }}" required>
            @error('nama')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary" type="submit">Simpan</button>
        <a href="{{ route('kategoris.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
