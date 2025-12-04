<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MasterItemsExport;



class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
{
    $kode     = $request->kode;
    $nama     = $request->nama;
    $hargamin = $request->hargamin;
    $hargamax = $request->hargamax;

    // Bersihkan input harga (kalau ada titik/koma/spasi)
    if ($hargamin !== null && $hargamin !== '') {
        $hargamin = (int) str_replace(['.', ',', ' '], '', $hargamin);
    } else {
        $hargamin = null;
    }

    if ($hargamax !== null && $hargamax !== '') {
        $hargamax = (int) str_replace(['.', ',', ' '], '', $hargamax);
    } else {
        $hargamax = null;
    }

    // Query dasar + relasi kategori
    $query = MasterItem::with('kategoris');

    if ($kode !== null && $kode !== '') {
        $query->where('kode', 'LIKE', '%'.$kode.'%');
    }

    if ($nama !== null && $nama !== '') {
        $query->where('nama', 'LIKE', '%'.$nama.'%');
    }

    $items = $query
        ->orderBy('id')
        ->get(['id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto']);

    // FILTER DI PHP BERDASARKAN HARGA JUAL
    $filtered = $items->filter(function ($item) use ($hargamin, $hargamax) {
        $hargaJual = (int) round($item->harga_beli + $item->harga_beli * $item->laba / 100);

        if ($hargamin !== null && $hargaJual < $hargamin) {
            return false;
        }

        if ($hargamax !== null && $hargaJual > $hargamax) {
            return false;
        }

        return true;
    });

    // Bentuk data untuk frontend
    $data = $filtered->values()->map(function ($item) {
        $hargaJual = (int) round($item->harga_beli + $item->harga_beli * $item->laba / 100);

        return [
            'kode'          => $item->kode,
            'nama'          => $item->nama,
            'jenis'         => $item->jenis,
            'harga_beli'    => $item->harga_beli,
            'laba'          => $item->laba,
            'supplier'      => $item->supplier,
            'harga_jual'    => $hargaJual,
            // 🔥 ini ambil nama kategori dari relasi yang baru bener
            'kategori_nama' => $item->kategoris->pluck('nama')->join(', '),
            'foto_url'      => $item->foto ? Storage::url($item->foto) : null,
        ];
    });

    return response()->json([
        'status' => 200,
        'data'   => $data,
    ]);
}

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem(); 
        } else {
            $item = MasterItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategoris'] = Kategori::orderBy('nama')->get();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
        $data_item->save();

    // foto
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $path = $file->store('fotos', 'public'); 
        $data_item->foto = $path;                
    }

    $data_item->save();

    $kategoriIds = $request->input('kategori_ids', []); 
    $data_item->kategoris()->sync($kategoriIds);

    return redirect('master-items')->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }

    public function exportExcel()
    {
        $fileName = 'master_items_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(new MasterItemsExport, $fileName);
    }

}
