<?php

use App\Events\UpdateAct;
use App\Http\Controllers\ActController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PageController::class, 'home'])->name('welcome');

Route::get('/dashboard', [PageController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/stream', [PageController::class, 'stream'])->middleware(['auth', 'verified'])->name('stream');
Route::get('/screen', [PageController::class, 'screen'])->middleware(['auth', 'verified'])->name('screen');
Route::get('/backstage', [PageController::class, 'backstage'])->middleware(['auth', 'verified'])->name('backstage');

Route::post('/act/launch', [ActController::class, 'launch'])->middleware(['auth', 'verified'])->name('act.launch');
Route::post('/act/start', [ActController::class, 'start'])->middleware(['auth', 'verified'])->name('act.start');
Route::get('/act/{act}/edit', [PageController::class, 'editAct'])->middleware(['auth', 'verified'])->name('act.edit');
Route::put('/act/{act}', [ActController::class, 'update'])->middleware(['auth', 'verified'])->name('act.update');

Route::post('/act/update-all', [ActController::class, 'updateAll'])->middleware(['auth', 'verified'])->name('act.updateAll');

require __DIR__ . '/auth.php';
