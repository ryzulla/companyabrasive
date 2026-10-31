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
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [AboutController::class, 'index'])->name('about');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/{id}', [ProductController::class, 'legacyRedirect'])->whereNumber('id');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/sitemap.xml', [ProductController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', function () {
    $noindex = \App\Models\Setting::get('seo_noindex', '0') === '1';
    $body = $noindex
        ? "User-agent: *\nDisallow: /\n"
        : "User-agent: *\nDisallow: /admin\nDisallow: /login\nDisallow: /products/search\n\nSitemap: " . url('/sitemap.xml') . "\n";

    return response($body, 200)->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('robots');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('videos', VideoController::class)->except(['show']);
    Route::resource('faqs', FaqController::class)->except(['show']);
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::post('blogs/upload-image', [BlogController::class, 'uploadImage'])->name('blogs.upload-image');
    Route::resource('blogs', BlogController::class)->except(['show']);

    Route::get('contacts', [ContactMessageController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contactMessage}', [ContactMessageController::class, 'show'])->name('contacts.show');
    Route::post('contacts/{contactMessage}/reply-email', [ContactMessageController::class, 'replyEmail'])->name('contacts.reply-email');
    Route::delete('contacts/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contacts.destroy');

    Route::get('account', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('account', [AccountController::class, 'update'])->name('account.update');
    Route::put('account/password', [AccountController::class, 'password'])->name('account.password');

    // Master data & pengaturan: hanya Superuser
    Route::middleware('superuser')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('clients', ClientController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
