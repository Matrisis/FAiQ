<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\Parameters;
use App\Http\Controllers\TeamPromptController;
use App\Http\Middleware\Maintenance;
use App\Models\Team;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware([])->prefix("/{team}")->name("public.")->group(function () {

    Route::middleware([Maintenance::class])->prefix('/ask')->name('ask.')->group(function () {
        Route::get('/', [AskController::class, 'index'])->name('index');
        Route::post("/", [AskController::class, 'create'])->name('create');
        Route::post("/vote/{answer}", [AskController::class, 'vote'])->name('vote');
        Route::get("/maintenance", function () {
            return Inertia::render("Maintenance");
        })->name('maintenance');
    });
    Route::prefix('/ask')->name('ask.')->group(function () {
        Route::get("/maintenance", function (Team $team) {
            if($team->parameters->accessible)
                return redirect()->route('public.ask.index', ['team' => $team->id]);
            return Inertia::render("Maintenance");
        })->name('maintenance');
    });

});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->name("admin.")
    ->prefix("/admin")
    ->group(function () {

    Route::prefix("/{team}/")->group(function () {
        Route::prefix('/files')->name('files.')->group(function () {
            Route::get('/', [FileController::class, 'index'])->name('index');
            Route::get('/list', [FileController::class, 'list'])->name('list');
            Route::post('/store', [FileController::class, 'store'])->name('store');
            Route::delete('/delete/{file}', [FileController::class, 'delete'])->name('delete');
            Route::put('/process/{file}', [FileController::class, 'process'])->name('process');
        });

        Route::prefix('/parameters')->name('parameters.')->group(function () {
            Route::get('/', [Parameters::class, 'index'])->name('index');
            Route::put('/update/{params}', [Parameters::class, 'update'])->where('params', '[0-9]+')->name('update');
        });

        Route::prefix('/prompt')->name('prompt.')->group(function () {
            Route::get('/', [TeamPromptController::class, 'index'])->name('index');
            Route::put('/update', [TeamPromptController::class, 'update'])->name('update');
        });

        Route::prefix("/answers")->name("answers.")->group(function () {
            Route::get("/", [AnswerController::class, 'index'])->name("index");
            Route::get("/list", [AnswerController::class, 'get'])->name("get");
            Route::put("/{answer}", [AnswerController::class, 'update'])->name("update");
            Route::delete("/{answer}", [AnswerController::class, 'delete'])->name("delete");
        });

    });

    Route::prefix('/management')->name('management.')->group(function () {
        Route::get('/', [ManagementController::class, 'index'])->name('index');
        Route::get('/list', [ManagementController::class, 'list'])->name('list');
        Route::put('/unlock/{team}', [ManagementController::class, 'unlock'])->name('unlock');
        Route::get('/view/{team}', [ManagementController::class, 'viewTeam'])->name('view');
    });

});
