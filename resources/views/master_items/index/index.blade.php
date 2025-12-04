@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{-- Baris tombol aksi --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <a href="{{ url('master-items/form/new') }}" class="btn btn-secondary">
                        + Master Items Baru
                    </a>
                </div>
                <div>
                    <a href="{{ route('master-items.export-excel') }}" class="btn btn-success">
                        Download Excel
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Daftar Master Items</div>

                <div class="card-body">
                    @include('master_items.index.filter')
                    @include('master_items.index.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    @include('master_items.index.js')
@endsection
