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

    // Category Routes
    Route::get('/categories',[AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/Add_categories', [AdminController::class, 'addCategories'])->name('admin.addcategories');
    Route::post('/Add_categories',[AdminController::class, 'saveCategory'])->name('admin.savecategories');
    Route::put('/updateCategories',[AdminController::class,'updateCategories'])->name('admin.updatecategories');
    Route::get('/editCategories/{id}',[AdminController::class,'editCategories'])->name('admin.editCategories');
    Route::delete('/delete_categories/{id}',[AdminController::class,'deleteCategories'])->name('admin.deleteCategories');


});

require __DIR__.'/auth.php';
