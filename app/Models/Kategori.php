<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'nama',
        'kode',
    ];

    public function masterItems()
    {
        return $this->belongsToMany(
            MasterItem::class,
            'kategori_master_item',
            'kategori_id',    // FK ke kategoris.id
            'master_item_id'  // FK ke master_items.id
        );
    }
}
