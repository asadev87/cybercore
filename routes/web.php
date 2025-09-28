<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\BadgesController;
use App\Http\Controllers\Admin\SectionController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/_mail/test', function () {
    Mail::raw('CyberCore mail test OK.', function ($m) {
        $m->to('asapmj87@gmail.com')->subject('CyberCore SMTP test');
    });
    return 'Sent.';
});

// Admin & Lecturer Routes
Route::middleware(['auth', 'verified', 'role:admin|lecturer'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('modules', ModuleController::class);
        Route::resource('modules.questions', QuestionController::class);
        Route::post('modules/{module}/questions/import', [ImportController::class, 'questions'])->name('modules.questions.import');
        Route::get('modules/{module}/questions/template', [ImportController::class, 'template'])->name('modules.questions.template');
        Route::resource('modules.sections', SectionController::class);

        // Admin-only routes
        Route::middleware('role:admin')->group(function () {
            Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
            Route::get('reports/export/excel', [ReportsController::class, 'exportExcel'])->name('reports.export.excel');
            Route::get('reports/export/pdf', [ReportsController::class, 'exportPdf'])->name('reports.export.pdf');
        });
    });

// User (Student) Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/learn', [LearnController::class, 'index'])->name('learn.index');
    Route::get('/learn/{module:slug}', [LearnController::class, 'show'])->name('learn.show');
    Route::get('/learn/{module:slug}/start', [QuizController::class, 'start'])->name('learn.start');

    // Quiz routes
    Route::post('/quiz/{module}/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::get('/quiz/{attempt}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{attempt}/answer', [QuizController::class, 'answer'])->name('quiz.answer');
    Route::post('/quiz/{attempt}/finish', [QuizController::class, 'finish'])->name('quiz.finish');
    Route::get('/quiz/{attempt}/result', [QuizController::class, 'result'])->name('quiz.result');

    // User account, performance, etc.
    Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::patch('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/badges', [BadgesController::class, 'index'])->name('badges.index');

    // Certificate routes
    Route::get('/attempts/{attempt}/certificate', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/view', [CertificateController::class, 'show'])->name('certificates.view');
    Route::get('/certificates/{certificate}/embed', [CertificateController::class, 'embed'])->name('certificates.embed');
    Route::get('/certificates/{certificate}/stream', [CertificateController::class, 'stream'])->name('certificates.stream');
});

require __DIR__.'/auth.php';