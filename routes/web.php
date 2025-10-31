<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Auth\EmailOtpController;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\BadgesController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\NoteFeedbackController;


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

// Protect admin routes

//Route::middleware(['auth'])->group(function () {
    //Route::get('/email/verify-otp', [EmailOtpController::class, 'showVerifyForm'])->name('verification.otp.notice');
    //Route::post('/email/otp/send', [EmailOtpController::class, 'send'])->name('verification.otp.send');
   // Route::post('/email/otp/verify', [EmailOtpController::class, 'verify'])>name('verification.otp.verify');
//});

Route::get('/_mail/test', function () {
    Mail::raw('CyberCore mail test OK.', function ($m) {
        $m->to('asapmj87@gmail.com')->subject('CyberCore SMTP test');
    });
    return 'Sent.';
});


//Admin
Route::prefix('admin')->name('admin.')->middleware(['auth','verified'])->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::get('reports/export/excel', [ReportsController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('reports/export/pdf', [ReportsController::class, 'exportPdf'])->name('reports.export.pdf');
    });

    Route::middleware('role:admin|lecturer')->group(function () {
        Route::resource('modules', ModuleController::class);
        Route::get('modules/{module}/builder', [ModuleController::class, 'builder'])->name('modules.builder');
        Route::resource('modules.sections', SectionController::class);
        Route::resource('modules.questions', QuestionController::class);
        Route::post('modules/{module}/questions/import', [ImportController::class, 'questions'])->name('modules.questions.import');
        Route::get('modules/{module}/questions/template', [ImportController::class, 'template'])->name('modules.questions.template');
    });
});


//Users
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/learn', [LearnController::class, 'index'])->name('learn.index');
    Route::post('/notes/feedback', [NoteFeedbackController::class, 'store'])->name('notes.feedback.store');

    // Start (or resume) a quiz attempt for a module
    Route::post('/quiz/{module}/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::get('/learn/{module:slug}/start', [QuizController::class, 'start'])->name('learn.start');

    // Show current question / submit answer / finish
    Route::get('/quiz/{attempt}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{attempt}/answer', [QuizController::class, 'answer'])->name('quiz.answer');
    Route::post('/quiz/{attempt}/finish', [QuizController::class, 'finish'])->name('quiz.finish');
    Route::post('/quiz/{attempt}/instructions', [QuizController::class, 'acknowledgeInstructions'])->name('quiz.instructions');

    // Result
    Route::get('/quiz/{attempt}/result', [QuizController::class, 'result'])->name('quiz.result');

    Route::get('/learn/{module:slug}', [\App\Http\Controllers\LearnController::class,'show'])->name('learn.show');

});


Route::middleware(['auth','verified'])->group(function(){
  Route::get('/certificates/{certificate}/view', [CertificateController::class,'show'])
    ->name('certificates.view');     // raw inline stream
  Route::get('/certificates/{certificate}', [CertificateController::class,'embed'])
    ->name('certificates.embed');    // HTML wrapper
  Route::get('/certificates/{certificate}/stream', [CertificateController::class,'stream'])
    ->name('certificates.stream');
  Route::get('/certificates/{certificate}/download', [CertificateController::class,'download'])
    ->name('certificates.download');

  Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
  Route::get('/account', [AccountController::class, 'index'])->name('account.index');
  Route::patch('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
  Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
  Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
  Route::get('/attempts/{attempt}/certificate', [CertificateController::class,'show'])->name('certificates.show');
  Route::get('/badges', [BadgesController::class, 'index'])->name('badges.index');
});


// login: 5 attempts per minute per email+IP
//RateLimiter::for('login', function (Request $request) {
    //$key = strtolower($request->input('email')).'|'.$request->ip();
    //return [Limit::perMinute(5)->by($key)];
//});

// otp: 5 actions per minute per IP (send/verify)
//RateLimiter::for('otp', function (Request $request) {
 //   return [Limit::perMinute(5)->by($request->ip())];
//});

require __DIR__.'/auth.php';
