<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $query = Kategori::query();

        if ($kode) {
            $query->where('kode', 'LIKE', '%'.$kode.'%');
        }

        if ($nama) {
            $query->where('nama', 'LIKE', '%'.$nama.'%');
        }

        $kategoris = $query->orderBy('kode')->paginate(10);

        return view('kategoris.index', compact('kategoris', 'kode', 'nama'));
    }

    public function create()
    {
        return view('kategoris.form', [
            'kategori' => new Kategori(),
            'method'   => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:kategoris,kode',
            'nama' => 'required',
        ]);

        Kategori::create($request->only('kode', 'nama'));

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil dibuat');
    }

    public function show(Kategori $kategori)
    {
        // eager load master items
        $kategori->load('masterItems');

        return view('kategoris.show', compact('kategori'));
    }

    public function edit(Kategori $kategori)
    {
        return view('kategoris.form', [
            'kategori' => $kategori,
            'method'   => 'edit',
        ]);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'kode' => 'required|unique:kategoris,kode,' . $kategori->id,
            'nama' => 'required',
        ]);

        $kategori->update($request->only('kode', 'nama'));

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()->route('kategoris.index')->with('success', 'Kategori berhasil dihapus');
    }

    public function print(Kategori $kategori)
{
    // load relasi item
    $kategori->load('masterItems');

    // data yang akan dilempar ke view pdf
    $data = [
        'kategori' => $kategori,
        'items'    => $kategori->masterItems,
        'printed_at' => now(), // waktu cetak
    ];


    $pdf = Pdf::loadView('kategoris.pdf', $data)->setPaper('a4', 'portrait');

    $fileName = 'kategori-'.$kategori->kode.'.pdf';

    return $pdf->download($fileName);
}

}
