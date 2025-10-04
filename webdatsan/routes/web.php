<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SanBongController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThanhToanController;
use App\Http\Controllers\Admin\SanBongAdminController;
use App\Http\Controllers\DatSanController;
use App\Http\Controllers\Admin\CustomerController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Sân Bóng
Route::get('/dat-san', [SanBongController::class, 'indexClient'])->name('san.index');

// Thanh Toán
Route::middleware(['auth'])->group(function () {
    Route::get('/thanh-toan', [ThanhToanController::class, 'index'])->name('thanh-toan.index');
    Route::get('/thanh-toan/{sanBongId}/create', [ThanhToanController::class, 'create'])->name('thanh-toan.create');
    Route::post('/thanh-toan/{sanBongId}', [ThanhToanController::class, 'store'])->name('thanh-toan.store');
    Route::post('/thanh-toan/{id}/cancel', [ThanhToanController::class, 'cancel'])->name('thanh-toan.cancel');
});

// Đặt Sân
Route::middleware(['auth'])->group(function () {
    Route::get('/datsan/{sanId}/create', [DatSanController::class, 'create'])->name('datsan.create');
    Route::post('/datsan/{sanId}', [DatSanController::class, 'store'])->name('datsan.store');
    Route::get('/san-da-dat', [DatSanController::class, 'index'])->name('sanda-dat.index');
    Route::post('/huy-dat-san/{id}', [DatSanController::class, 'huyDatSan'])
    ->name('datsan.huy');
    Route::get('/dat-san/{id}/edit', [DatSanController::class, 'edit'])->name('datsan.edit')->middleware('auth');
    Route::put('/dat-san/{id}', [DatSanController::class, 'update'])->name('datsan.update')->middleware('auth');
});


// Quản trị viên


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('khach-hang', CustomerController::class);
    Route::get('khach-hang/{id}/lich-su-dat-san', [CustomerController::class, 'bookingHistory'])
        ->name('khach-hang.lich-su-dat-san');
    Route::get('khach-hang/{id}/lich-su-thanh-toan', [CustomerController::class, 'paymentHistory'])
        ->name('khach-hang.lich-su-thanh-toan');
});

Route::prefix('admin')->middleware(['auth', 'is_admin'])->name('admin.')->group(function () {
    Route::resource('san-bong', SanBongAdminController::class);
    
    Route::patch('/san-bong/{id}/toggle-status', [SanBongAdminController::class, 'toggleStatus'])
        ->name('san-bong.toggle-status');
});



require __DIR__.'/auth.php';
