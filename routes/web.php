<?php

use App\Http\Controllers\UrlCheckController;
use App\Http\Controllers\UrlController;
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

Route::get('/urls', [UrlController::class, 'index'])->name('urls');
Route::get('/urls/{url}', [UrlController::class, 'show'])->name('urls.show');
Route::post('/urls', [UrlController::class, 'store'])->name('urls.store');
Route::post('/url/{id}/checks', [UrlCheckController::class, 'store'])->name('url.checks');
