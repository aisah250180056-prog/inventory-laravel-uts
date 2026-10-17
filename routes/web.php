<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; use App\Http\Controllers\ProductController; use App\Http\Controllers\DashboardController; use App\Http\Controllers\CategoryController; use App\Http\Controllers\SupplierController; use App\Http\Controllers\StockController; use App\Http\Controllers\ReportController; use App\Http\Controllers\UserController;use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
Route::get('/login',[AuthController::class,'showLogin'])->name('login')->middleware('guest');
Route::post('/login',[AuthController::class,'login'])->name('login.store')->middleware('guest');
Route::post('/logout',[AuthController::class,'logout'])->name('logout')->middleware('auth');
Route::middleware('auth')->group(function(){Route::get('/purchases', [PurchaseController::class, 'index'])
    ->name('purchases.index');

Route::get('/purchases/create', [PurchaseController::class, 'create'])
    ->name('purchases.create');

Route::post('/purchases', [PurchaseController::class, 'store'])
    ->name('purchases.store');
 Route::get('/',fn()=>redirect()->route('dashboard'));
 Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
 Route::resource('products',ProductController::class);
 Route::get('/categories',[CategoryController::class,'index'])->name('categories.index'); Route::post('/categories',[CategoryController::class,'store'])->name('categories.store'); Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update'); Route::delete('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.destroy');
 Route::get('/suppliers',[SupplierController::class,'index'])->name('suppliers.index'); Route::post('/suppliers',[SupplierController::class,'store'])->name('suppliers.store'); Route::put('/suppliers/{supplier}',[SupplierController::class,'update'])->name('suppliers.update'); Route::delete('/suppliers/{supplier}',[SupplierController::class,'destroy'])->name('suppliers.destroy');
 Route::get('/stock',[StockController::class,'index'])->name('stock.index'); Route::post('/stock',[StockController::class,'store'])->name('stock.store');
 Route::get('/reports',[ReportController::class,'index'])->name('reports.index'); Route::get('/users',[UserController::class,'index'])->name('users.index'); Route::post('/users',[UserController::class,'store'])->name('users.store');
});
