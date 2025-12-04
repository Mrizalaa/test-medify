<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterItemsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $rowNumber = 0;

    public function collection()
    {
        // ambil semua item + relasi kategori
        return MasterItem::with('kategoris')
            ->orderBy('id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Items',
            'Nama Supplier',
            'Harga',
            'Laba (%)',
            'Harga Jual',
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;

        $kategoriNama = $item->kategoris->pluck('nama')->join(', ');

        $hargaJual = (int) round(
            $item->harga_beli + $item->harga_beli * $item->laba / 100
        );

        return [
            $this->rowNumber,      // No
            $kategoriNama,         // Nama Kategori (dipisah koma)
            $item->nama,           // Nama Items
            $item->supplier,       // Nama Supplier
            $item->harga_beli,     // Harga
            $item->laba,           // Laba
            $hargaJual,            // Harga Jual
        ];
    }
}
