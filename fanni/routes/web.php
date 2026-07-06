<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ClientController;
use Illuminate\Support\Facades\Route;

// Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [AboutController::class, 'index'])->name('about');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('videos', VideoController::class)->except(['show']);
    Route::resource('faqs', FaqController::class)->except(['show']);
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('blogs', BlogController::class)->except(['show']);
    Route::resource('clients', ClientController::class)->except(['show']);

    Route::get('contacts', [ContactMessageController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contactMessage}', [ContactMessageController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contacts.destroy');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
