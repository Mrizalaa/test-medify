<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MasterItemsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/master-items', [App\Http\Controllers\MasterItemsController::class, 'index']);
Route::get('/master-items/search', [App\Http\Controllers\MasterItemsController::class, 'search']);
Route::get('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formView']);
Route::post('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formSubmit']);

Route::get('/master-items/view/{kode}', [App\Http\Controllers\MasterItemsController::class, 'singleView']);
Route::get('/master-items/delete/{id}', [App\Http\Controllers\MasterItemsController::class, 'delete']);


Route::get('/master-items/update-random-data', [App\Http\Controllers\MasterItemsController::class, 'updateRandomData']);
Route::post('/upload-foto', [FotoController::class, 'upload']);

Route::get('kategoris', [KategoriController::class, 'index'])->name('kategoris.index');
Route::get('kategoris/create', [KategoriController::class, 'create'])->name('kategoris.create');
Route::post('kategoris', [KategoriController::class, 'store'])->name('kategoris.store');
Route::get('kategoris/{kategori}', [KategoriController::class, 'show'])->name('kategoris.show');
Route::get('kategoris/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategoris.edit');
Route::put('kategoris/{kategori}', [KategoriController::class, 'update'])->name('kategoris.update');
Route::delete('kategoris/{kategori}', [KategoriController::class, 'destroy'])->name('kategoris.destroy');

Route::get('kategoris/{kategori}/print', [KategoriController::class, 'print'])
    ->name('kategoris.print');

Route::get('master-items/export-excel', [MasterItemsController::class, 'exportExcel'])
    ->name('master-items.export-excel');