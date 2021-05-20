<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

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

Route::get('/index', [PdfController::class, 'index'])->middleware(['auth'])->name('pdf.index');
Route::get('/index/{id}', [PdfController::class, 'show'])->middleware(['auth'])->name('pdf.show');
Route::delete('/delete/{id}', [PdfController::class, 'destroy'])->middleware(['auth'])->name('pdf.destroy');
Route::post('/pdf/create', [PdfController::class, 'store'])->middleware(['auth'])->name('pdf.store');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';
