<?php

use App\Http\Controllers\AskController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Parameters;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::middleware([])->prefix("/{team}")->name("public.")->group(function () {

    Route::prefix('/ask')->name('ask.')->group(function () {
        Route::get('/', [AskController::class, 'index'])->name('index');
        Route::post("/", [AskController::class, 'create'])->name('create');
        Route::post("/vote/{answer}", [AskController::class, 'vote'])->name('vote');
    });

});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->name("admin.")
    ->prefix("/{team}/admin")
    ->group(function () {

    Route::prefix('/files')->name('files.')->group(function () {
        Route::get('/', [FileController::class, 'index'])->name('index');
    });

    Route::prefix('/parameters')->name('parameters.')->group(function () {
        Route::get('/', [Parameters::class, 'index'])->name('index');
        Route::put('/update/{params}', [Parameters::class, 'update'])->where('params', '[0-9]+')->name('update');
    });

});
