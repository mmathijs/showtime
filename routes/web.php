<?php

use App\Events\UpdateAct;
use App\Http\Controllers\ActController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/dashboard', [PageController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/stream', [PageController::class, 'stream'])->middleware(['auth', 'verified'])->name('stream');
Route::get('/screen', [PageController::class, 'screen'])->middleware(['auth', 'verified'])->name('screen');

Route::post('/act/launch', [ActController::class, 'launch'])->middleware(['auth', 'verified'])->name('act.launch');

require __DIR__ . '/auth.php';
