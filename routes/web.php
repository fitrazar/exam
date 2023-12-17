<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false, 'password.reset' => false, 'password.request' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/admin', function () {
        return view('admin');
    });
});

Route::middleware(['auth:student', 'auth.session', 'student'])->group(function () {
    Route::get('/siswa', function () {
        return view('siswa');
    });
});
