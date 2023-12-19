<?php

use App\Livewire\IndexExam;
use App\Livewire\IndexStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ExamController;
use App\Http\Controllers\Dashboard\GradeController;
use App\Http\Controllers\Dashboard\GroupController;
use App\Http\Controllers\Dashboard\MajorController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\Dashboard\DashboardController;

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

Auth::routes(['register' => false, 'password.reset' => false, 'password.request' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    Route::resource('/grade', GradeController::class)->except('show');
    Route::resource('/major', MajorController::class)->except('show');
    Route::resource('/group', GroupController::class)->except('show');
    Route::resource('/student', StudentController::class)->except('show');
    Route::resource('/exam', ExamController::class)->except('show');
    Route::get('/exam/question/{question}/delete', [ExamController::class, 'deleteQuestion']);
    // Import
    Route::post('/student/import', [StudentController::class, 'import']);
    Route::post('/exam/import', [ExamController::class, 'import']);
});

Route::middleware(['auth:student', 'student'])->group(function () {
    Route::get('/', IndexStudent::class);
    Route::get('/exam/{exam}', IndexExam::class);
});
