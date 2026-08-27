<?php

use App\Http\Controllers\Admin\AwinSyncController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MerchantController as AdminMerchantController;
use App\Http\Controllers\Admin\MerchantOfferController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\GoRedirectController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\NewsletterController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $locale = SetLocale::resolvePreferredLocale(request());

    return redirect("/{$locale}");
});

Route::get('/go/{merchant:slug}', GoRedirectController::class)->name('go');

Route::prefix('{locale}')->where(['locale' => 'de|en'])->middleware('locale')->group(function () {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/stores', [MerchantController::class, 'index'])->name('stores.index');
    Route::get('/store/{merchant:slug}', [MerchantController::class, 'show'])->name('stores.show');

    Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

    Route::get('/impressum', [LegalController::class, 'impressum'])->name('legal.impressum');
    Route::get('/datenschutz', [LegalController::class, 'privacy'])->name('legal.privacy');
    Route::get('/terms', [LegalController::class, 'terms'])->name('legal.terms');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

    require __DIR__.'/auth.php';

    Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        Route::view('settings', 'admin.settings')->name('settings');

        Route::get('/merchant-offer/create', [MerchantOfferController::class, 'create'])->name('merchant-offer.create');
        Route::post('/merchant-offer', [MerchantOfferController::class, 'store'])->name('merchant-offer.store');

        Route::resource('merchants', AdminMerchantController::class)->except(['show', 'destroy']);
        Route::patch('/merchants/{merchant}/toggle-status', [AdminMerchantController::class, 'toggleStatus'])->name('merchants.toggle-status');

        Route::middleware('can-manage-categories')->group(function () {
            Route::resource('categories', AdminCategoryController::class)->except(['show']);
            Route::patch('/categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        });

        Route::resource('offers', AdminOfferController::class)->except(['show']);
        Route::patch('/offers/{offer}/publish', [AdminOfferController::class, 'publish'])->name('offers.publish');
        Route::patch('/offers/{offer}/toggle-verified', [AdminOfferController::class, 'toggleVerified'])->name('offers.toggle-verified');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');

        Route::get('/reports/export', [AdminDashboardController::class, 'exportCsv'])->name('reports.export');

        Route::get('/awin', [AwinSyncController::class, 'index'])->name('awin.index');
        Route::post('/awin/sync', [AwinSyncController::class, 'sync'])->middleware('can-run-awin-sync')->name('awin.sync');
    });
});
