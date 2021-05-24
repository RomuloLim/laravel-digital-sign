<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/index', [PdfController::class, 'index'])->name('pdf.index');
Route::get('/index', [PdfController::class, 'index'])->name('pdf.index');
Route::get('/index/{id}', [PdfController::class, 'show'])->name('pdf.show');
Route::delete('/delete/{id}', [PdfController::class, 'destroy'])->name('pdf.destroy');
Route::post('/pdf/create', [PdfController::class, 'store'])->name('pdf.store');
Route::post('/user/create', [UserController::class, 'store'])->name('user.store');
Route::get('/pdf/signPage/{id}', [PdfController::class, 'signPage'])->name('pdf.signPage');
Route::post('/pdf/sign/{id}', [PdfController::class, 'sign'])->name('pdf.sign');

