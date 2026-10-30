<?php

use App\Http\Controllers\Admin\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::get('/', function () {
    // Dummy data katalog (Nanti ini diganti dengan query ke Database)
    $products = Product::all();

    return Inertia::render('Home', [
        'products' => $products,
        // Nanti ganti dengan nomor WA bot cadanganmu
        'waNumber' => '6281234567890' 
    ]);
});

Route::prefix('admin')->group(function () {
    Route::get('/products', [ProductController::class,'index']);
    Route::get('/products/create', [ProductController::class,'create']);
    Route::post('/products', [ProductController::class,'store']);

    Route::get('/products/{product}/edit', [ProductController::class,'edit']);
    Route::put('/products/{product}', [ProductController::class,'update']);
    Route::delete('/products/{product}', [ProductController::class,'delete']);
});