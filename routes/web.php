<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParonamaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\GlobalWebsiteData;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([GlobalWebsiteData::class])->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('index');
});

Route::get('/change-language/api-call', function () {
    $currentLocale = App::getLocale();

    $newLocale = $currentLocale === 'vi' ? 'en' : 'vi';

    // App::setLocale($newLocale);

    Session::put('locale', $newLocale);

    return response()->json(['status' => 'success', 'new_locale' => $newLocale]);
});

Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::get('/api/get-location-infomation', [PageController::class, 'api'])->name('api');

Route::post('/login/auth', [AuthController::class, 'auth_check'])->name('login.auth');

// Route::get('/register', [AuthController::class, 'register'])->name('register');

// Route::post('/register/auth', [AuthController::class, 'register_check'])->name('register.auth');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/gallery', [AdminController::class, 'gallery'])->name('gallery');

    Route::get('/gallery/add', [AdminController::class, 'gallery_add'])->name('gallery.add');

    Route::post('/gallery/store', [AdminController::class, 'gallery_store'])->name('gallery.store');

    Route::post('/gallery/delete', [AdminController::class, 'gallery_delete'])->name('gallery.delete');

    Route::get('/location', [AdminController::class, 'location'])->name('location');

    Route::get('/location/add', [AdminController::class, 'location_add'])->name('location.add');

    Route::get('/location/edit', [AdminController::class, 'location_edit'])->name('location.edit');

    Route::post('/location/store', [AdminController::class, 'location_store'])->name('location.store');

    Route::post('/location/edit/update/{id}', [AdminController::class, 'location_update'])->name('location.update');

    Route::post('/location/delete', [AdminController::class, 'location_delete'])->name('location.delete');

    Route::post('/location/hotspot/store', [AdminController::class, 'location_hotspot_store'])->name('location.hotspot.store');

    Route::post('/location/hotspot/delete', [AdminController::class, 'location_hotspot_delete'])->name('location.hotspot.delete');

    Route::post('/location/special_hotspot/store', [AdminController::class, 'location_special_hotspot_store'])->name('location.special.hotspot.store');

    Route::post('/location/special_hotspot/delete', [AdminController::class, 'location_special_hotspot_delete'])->name('location.special.hotspot.delete');

    Route::post('/location/list/update', [AdminController::class, 'location_list_update'])->name('location.list.update');

    Route::post('/location/eye/set', [AdminController::class, 'location_eye_set'])->name('location.eye.set');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.auth');

    Route::get('/category', [AdminController::class, 'category'])->name('category');

    Route::get('/category/add', [AdminController::class, 'category_add'])->name('category.add');

    Route::post('/category/add/store', [AdminController::class, 'category_store'])->name('category.store');

    Route::post('/category/edit/store', [AdminController::class, 'category_update_store'])->name('category.update.store');

    Route::get('/category/edit/{id}', [AdminController::class, 'category_edit'])->name('category.edit');

    Route::post('/category/delete', [AdminController::class, 'category_delete'])->name('category.delete');

    Route::get('/setting', [AdminController::class, 'setting'])->name('setting');

    Route::post('/setting/store', [AdminController::class, 'setting_store'])->name('setting.store');
});
