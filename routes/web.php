<?php

declare(strict_types=1);

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SigningController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// Public Verification & Digital Signature Routes (Rate Limited)
Route::middleware('throttle:30,1')->group(function (): void {
    Route::get('/verify/{code}', [VerificationController::class, 'verify'])->name('verify.show');
    Route::get('/sign/{token}', [SigningController::class, 'show'])->name('sign.show');
    Route::post('/sign/{token}', [SigningController::class, 'sign'])->name('sign.process');
});

// Authenticated & Active User Routes
Route::middleware(['auth', 'active'])->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Berita Acara & Document Workflow
    Route::get('/arsip', [BeritaAcaraController::class, 'archiveIndex'])->name('berita-acara.archive');
    Route::get('/berita-acara/{beritaAcara}/print', [BeritaAcaraController::class, 'print'])->name('berita-acara.print');
    Route::post('/berita-acara/{beritaAcara}/handling', [BeritaAcaraController::class, 'storeHandlingLog'])->name('berita-acara.handling');
    Route::post('/berita-acara/{beritaAcara}/attachments', [BeritaAcaraController::class, 'storeAttachment'])->name('berita-acara.attachments.store');
    Route::get('/berita-acara/{beritaAcara}/attachments/{attachment}/download', [BeritaAcaraController::class, 'downloadAttachment'])->name('berita-acara.attachments.download');
    Route::post('/berita-acara/{beritaAcara}/request-signature', [SigningController::class, 'requestSignature'])->name('berita-acara.request-signature');
    Route::post('/berita-acara/{beritaAcara}/archive', [BeritaAcaraController::class, 'archive'])->name('berita-acara.archive-action');

    Route::resource('berita-acara', BeritaAcaraController::class);

    // Superadmin Only Routes
    Route::middleware('role:superadmin')->group(function (): void {
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update']);
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
