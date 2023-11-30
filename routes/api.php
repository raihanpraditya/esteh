<?php

use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get(/user, function (Request $request){
//     return $request->user();
// });

Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{id}', [ProductsController::class, 'show']);
Route::post('/products', [ProductsController::class, 'store']);
Route::put('/products/{id}', [ProductsController::class, 'update']);
Route::delete('/products/{id}', [ProductsController::class, 'destroy']);

Route::get('/transactions', [TransactionsController::class, 'index']);
Route::post('/transactions', [TransactionsController::class, 'store']);
Route::get('/transactions/{id}', [TransactionsController::class, 'show']);
Route::get('/transactions/success/{id}', [TransactionsController::class, 'setPaid']);


Route::get('/keranjang', [KeranjangController::class, 'index']);
Route::post('/keranjang', [KeranjangController::class, 'store']);
Route::post('/keranjang/delete', [KeranjangController::class, 'deleteKeranjang']);
Route::post('/keranjang/proses', [KeranjangController::class, 'processKeranjang']);
Route::get('/keranjang/sum', [KeranjangController::class, 'sumkeranjang']);
