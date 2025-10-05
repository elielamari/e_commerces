<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
Route::get('/', function () {
    return view('index');
})->name('index');



Route::get('/dashboard', [UserController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/add_brand', [AdminController::class, 'addBrand'])->name('admin.addbrand');
    Route::post('/add_brand', [AdminController::class, 'saveBrand'])->name('admin.savebrand');
    Route::get('/Brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('/editbrand/{id}',[AdminController::class,'editBrand'])->name('admin.editbrand');
    Route::put('/brand/updatebrand',[AdminController::class,'brandupdate'])->name('admin.brandupdate');
    Route::delete('/delete_brand/{id}',[AdminController::class,'deleteBrand'])->name('admin.deletebrand');

});

require __DIR__.'/auth.php';
