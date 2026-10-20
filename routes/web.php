<?php

use App\Http\Controllers\Admin\LandingAdminController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Publik Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('landing.public');
Route::get('/flyers/{flyer}', [LandingPageController::class, 'showFlyer'])->name('flyers.show');

// 2. Dashboard / Auth Bawaan Breeze
Route::get('/dashboard', function () {
    return redirect()->route('admin.landing.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 3. ADMIN PANEL
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/landing-page', [LandingAdminController::class, 'index'])->name('landing.index');

    // Tab 1: Banner
    Route::post('/landing-page/banner', [LandingAdminController::class, 'storeBanner'])->name('landing.store_banner');
    Route::delete('/landing-page/banner/delete-image', [LandingAdminController::class, 'deleteBannerImage'])->name('landing.delete_banner_image');
    Route::delete('/landing-page/banner/{id}', [LandingAdminController::class, 'destroyBanner'])->name('landing.destroy_banner');
    Route::get('/landing-page/banner/{id}/edit', [LandingAdminController::class, 'editBanner'])->name('landing.edit_banner');
    Route::put('/landing-page/banner/{id}', [LandingAdminController::class, 'updateBanner'])->name('landing.update_banner');
    // Tab 2: Partner
    Route::post('/landing-page/partner', [LandingAdminController::class, 'storePartner'])->name('landing.store_partner');
    Route::delete('/landing-page/partner/{id}', [LandingAdminController::class, 'destroyPartner'])->name('landing.destroy_partner');

    // Tab 3: Flyer
    Route::put('/landing-page/flyer-section', [LandingAdminController::class, 'updateFlyerSection'])->name('landing.update_flyer_section');
    Route::post('/landing-page/flyer', [LandingAdminController::class, 'storeFlyer'])->name('landing.store_flyer');
    Route::delete('/landing-page/flyer/{id}', [LandingAdminController::class, 'destroyFlyer'])->name('landing.destroy_flyer');

    // Tab 4: Syllabus
    Route::post('/landing-page/syllabus', [LandingAdminController::class, 'storeSyllabus'])->name('landing.store_syllabus');
    Route::delete('/landing-page/syllabus/{id}', [LandingAdminController::class, 'destroySyllabus'])->name('landing.destroy_syllabus');

    // Tab 5: Training History
    Route::post('/landing-page/history', [LandingAdminController::class, 'storeHistory'])->name('landing.store_history');
    Route::delete('/landing-page/history/{id}', [LandingAdminController::class, 'destroyHistory'])->name('landing.destroy_history');
});

require __DIR__.'/auth.php';
