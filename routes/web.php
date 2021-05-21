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

Route::middleware(['auth'])->group(function(){
Route::get('/index', [PdfController::class, 'index'])->name('pdf.index');
Route::get('/index/{id}', [PdfController::class, 'show'])->name('pdf.show');
Route::delete('/delete/{id}', [PdfController::class, 'destroy'])->name('pdf.destroy');
Route::post('/pdf/create', [PdfController::class, 'store'])->name('pdf.store');
Route::get('/pdf/signPage/{id}', [PdfController::class, 'signPage'])->name('pdf.signPage');
Route::post('/pdf/sign/{id}', [PdfController::class, 'sign'])->name('pdf.sign');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

});
require __DIR__.'/auth.php';
