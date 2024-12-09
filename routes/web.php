<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AskController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\Parameters;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TeamPromptController;
use App\Http\Middleware\LockedMiddleware;
use App\Http\Middleware\Maintenance;
use App\Http\Middleware\Subscribed;
use App\Models\Team;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix("/")->name("landing.")->group(function () {
    Route::get('/', function () {
        return Inertia::render('Welcome');
    })->name("home");
    Route::get('/pricing', function () {
        return Inertia::render('Welcome/Pricing');
    })->name("pricing");
    Route::prefix('/contact')->name("contact.")->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::post('/', [ContactController::class, 'create'])->name('create');
    });
});



Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', Subscribed::class, LockedMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->name("admin.")
    ->prefix("/admin")
    ->group(function () {

        Route::middleware([Subscribed::class, LockedMiddleware::class])->group(function () {
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
                    Route::post('/update/{params}', [Parameters::class, 'update'])->where('params', '[0-9]+')->name('update');
                });

                Route::prefix('/prompt')->name('prompt.')->group(function () {
                    Route::get('/', [TeamPromptController::class, 'index'])->name('index');
                    Route::put('/update', [TeamPromptController::class, 'update'])->name('update');
                });

                Route::prefix("/answers")->name("answers.")->group(function () {
                    Route::get("/", [AnswerController::class, 'index'])->name("index");
                    Route::get("/list", [AnswerController::class, 'get'])->name("get");
                    Route::post("/", [AnswerController::class, 'create'])->name("create");
                    Route::put("/{answer}", [AnswerController::class, 'update'])->name("update");
                    Route::delete("/{answer}", [AnswerController::class, 'delete'])->name("delete");
                });

                Route::prefix("/stats")->name("stats.")->group(function () {
                    Route::get('/', [StatsController::class, 'index'])->name('index');
                });

                Route::prefix("questions")->name("questions.")->group(function () {
                    Route::get("/", [QuestionsController::class, 'get'])->name("get");
                });


            });

            Route::prefix('/management')->name('management.')->group(function () {
                Route::get('/', [ManagementController::class, 'index'])->name('index');
                Route::get('/list', [ManagementController::class, 'list'])->name('list');
                Route::put('/unlock/{team}', [ManagementController::class, 'unlock'])->name('unlock');
                Route::get('/view/{team}', [ManagementController::class, 'viewTeam'])->name('view');
            });

        });

        // Billing Routes
        Route::prefix('/billing')->name('billing.')->group(function () {
            Route::get('/{team}/', [BillingController::class, 'index'])->name('index');
            Route::get('/{team}/checkout', [BillingController::class, 'checkout'])->name('checkout');
            Route::get('/{team}/success', [BillingController::class, 'success'])->name('success');
            Route::get('/{team}/cancel', [BillingController::class, 'cancel'])->name('cancel');
        });

        Route::prefix('/subscription')->name('subscription.')->group(function () {
            Route::get('/{team}/', [SubscriptionController::class, 'index'])->name('index');
            Route::get('/{team}/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
            Route::get('/{team}/resume', [SubscriptionController::class, 'resume'])->name('resume');
            Route::post('/{team}/refund', [SubscriptionController::class, 'refund'])->name('refund');
            Route::get('/{team}/invoice/{invoiceId}', [SubscriptionController::class, 'downloadInvoice'])->name('download');
        });
    });

Route::middleware([])->prefix("/{team}")->name("public.")->group(function () {

    Route::middleware([Maintenance::class])->prefix('/')->name('ask.')->group(function () {
        Route::get('/', [AskController::class, 'index'])->name('index');
        Route::post("/ask", [AskController::class, 'create'])->name('create');
        Route::post("/vote/{answer}", [AskController::class, 'vote'])->name('vote');
        Route::get("/maintenance", function () {
            return Inertia::render("Maintenance");
        })->name('maintenance');
    });

    Route::prefix('/')->name('ask.')->group(function () {
        Route::get("/maintenance", function (string $team) {
            $team = Team::where('slug', $team)->with('parameters')->firstOrFail();
            if ($team && $team->parameters->accessible && $team->subscribed($team->pricing->name)) {
                return redirect()->route('public.ask.index', ['team' => $team->slug]);
            }
            return Inertia::render("Maintenance");
        })->name('maintenance');
    });

});
