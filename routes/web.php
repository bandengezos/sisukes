<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

// Health Check for Fly.io
Route::get('/up', function () {
    return response('OK', 200);
});

// Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Public Survey Routes
Route::get('/survey/{id?}', [SurveyController::class, 'publicFill'])->name('survey.fill');
Route::post('/survey/{id}', [SurveyController::class, 'publicSubmit'])->name('survey.submit');

// Public Complaint Routes
Route::get('/pengaduan', [ComplaintController::class, 'publicCreate'])->name('pengaduan.create');
Route::post('/pengaduan', [ComplaintController::class, 'publicStore'])->name('pengaduan.store');

// Authenticated Admin/Staff Dashboard Routes
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    // Overwrite default dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Complaint Management
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/export/pdf', [ComplaintController::class, 'exportPdf'])->name('complaints.export.pdf');
    Route::get('/complaints/export/csv', [ComplaintController::class, 'exportCsv'])->name('complaints.export.csv');
    Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::put('/complaints/{id}/status', [ComplaintController::class, 'updateStatus'])->name('complaints.status');
    Route::post('/complaints/{id}/respond', [ComplaintController::class, 'respond'])->name('complaints.respond');

    // Survey Management
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
    Route::get('/surveys/{id}', [SurveyController::class, 'show'])->name('surveys.show');
    Route::delete('/surveys/{id}', [SurveyController::class, 'destroy'])->name('surveys.destroy');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
