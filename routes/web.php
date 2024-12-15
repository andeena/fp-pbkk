<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\AdminController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

route::get('/redirect',[HomeController::class,'redirect']);


route::get('/',[HomeController::class,'index']);

route::get('/product',[AdminController::class,'product']);

route::post('/uploadproduct',[AdminController::class,'uploadproduct']);

Route::get('/showproduct',[AdminController::class,'showproduct'])->name('showproduct');
 
Route::get('/deleteproduct/{id}',[AdminController::class,'deleteproduct'])->name('deleteproduct');

Route::get('/updateview/{id}',[AdminController::class,'updateview'])->name('updateview');

Route::post('/updateproduct/{id}',[AdminController::class,'updateproduct'])->name('updateproduct');

route::get('/search',[HomeController::class,'search']);

route::post('/addcart/{id}',[HomeController::class,'addcart']);

route::get('/showcart',[HomeController::class,'showcart']);

route::get('/delete/{id}',[HomeController::class,'deletecart']);

route::post('/order',[HomeController::class,'confirmorder']);

route::get('/showorder',[AdminController::class,'showorder']);

route::get('/updatestatus/{id}',[AdminController::class,'updatestatus']);

Route::get('/regenerate-barcode/{id}', [AdminController::class, 'regenerateBarcode'])->name('regenerate.barcode');

Route::get('/category/{category}', [HomeController::class, 'showProductsByCategory'])->name('category.show');

Route::post('/search', [HomeController::class, 'search'])->name('search');
