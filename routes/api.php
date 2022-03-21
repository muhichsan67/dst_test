<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login', [UserController::class, 'login']);
  
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('details', [UserController::class, 'details']);

    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{uuid}', [ProductController::class, 'detail']);
    Route::post('products', [ProductController::class, 'store']);
    Route::delete('products/{uuid}', [ProductController::class, 'delete']);

    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transactions/{uuid}', [TransactionController::class, 'detail']);
    Route::post('transactions', [TransactionController::class, 'store']);
});
