<form method="POST"
      action="{{ url('master-items/form/'.$method.'/'.($item->id ?? 0)) }}"
      enctype="multipart/form-data">
    @csrf

    @if($method == 'edit')
        <div class="mb-3">
            <label class="form-label">Kode Barang</label>
            <input type="text" class="form-control" name="kode_barang" readonly
                   value="{{ $item->kode ?? '' }}">
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" class="form-control" name="nama" required
               value="{{ old('nama', $item->nama ?? '') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required
               value="{{ old('harga_beli', $item->harga_beli ?? '') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required
               value="{{ old('laba', $item->laba ?? '') }}">
    </div>

    @php $selectedSupplier = $item->supplier ?? ''; @endphp
    <div class="mb-3">
        <label class="form-label">Supplier</label>
        <select class="form-select" required name="supplier">
            <option value="" @if($selectedSupplier == '') selected @endif>--Pilih--</option>
            <option value="Tokopaedi" @if($selectedSupplier == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option value="Bukulapuk" @if($selectedSupplier == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option value="TokoBagas" @if($selectedSupplier == 'TokoBagas') selected @endif>TokoBagas</option>
            <option value="E Commurz" @if($selectedSupplier == 'E Commurz') selected @endif>E Commurz</option>
            <option value="Blublu" @if($selectedSupplier == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selectedJenis = $item->jenis ?? ''; @endphp
    <div class="mb-3">
        <label class="form-label">Jenis</label>
        <select class="form-select" required name="jenis">
            <option value="" @if($selectedJenis == '') selected @endif>--Pilih--</option>
            <option value="Obat" @if($selectedJenis == 'Obat') selected @endif>Obat</option>
            <option value="Alkes" @if($selectedJenis == 'Alkes') selected @endif>Alkes</option>
            <option value="Matkes" @if($selectedJenis == 'Matkes') selected @endif>Matkes</option>
            <option value="Umum" @if($selectedJenis == 'Umum') selected @endif>Umum</option>
            <option value="ATK" @if($selectedJenis == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    {{-- 🔥 Field Kategori (many-to-many) --}}
    <div class="mb-3">
    <label for="kategori_id" class="form-label">Kategori</label>
    <select name="kategori_ids[]" id="kategori_id" class="form-select">
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategoris as $kategori)
            <option value="{{ $kategori->id }}"
                @if($item->exists && $item->kategoris->contains($kategori->id)) selected @endif>
                {{ $kategori->kode }} - {{ $kategori->nama }}
            </option>
        @endforeach
    </select>
</div>

    {{-- Foto --}}
    <div class="mb-3">
        <label class="form-label">Foto Barang</label>
        <input type="file" class="form-control" name="foto">

        @if(!empty($item->foto))
            <div class="mt-2">
                <small>Foto Saat Ini:</small><br>
                <img src="{{ Storage::url($item->foto) }}" width="120" class="img-thumbnail">
            </div>
        @endif
    </div>

    <button class="btn btn-primary mt-2">Submit</button>
</form>
