<?php

use App\Http\Controllers\AskController;
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
        Route::get('/', [AskController::class, 'indexPublic'])->name('index');
        Route::post("/", [AskController::class, 'create'])->name('create');
    });

});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->name("admin.")
    ->prefix("/{team}/admin")
    ->group(function () {

    Route::prefix('/ask')->name('ask.')->group(function () {
        Route::get('/', [AskController::class, 'indexAdmin'])->name('index');
    });

});
