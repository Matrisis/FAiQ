<?php

use App\Http\Controllers\AskController;
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
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});



Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->prefix("/{team}/admin")
    ->group(function () {

    Route::prefix('/ask')->name('ask.')->group(function () {
        Route::get('/', [AskController::class, 'index'])->name('index');
        Route::post("/", [AskController::class, 'create'])->name('create');
    });

});
