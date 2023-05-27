<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CheckOutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/login', [AuthController::class, 'login'])->name('user.login');

Route::middleware(['auth:sanctum', 'hasPermission'])->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register'])->name('user.register');

    Route::apiResource('/books', BookController::class);
    Route::post('/books/{book}/check_outs', [CheckOutController::class, 'store'])->name('check_outs.store');
    Route::apiResource('/check_outs', CheckOutController::class)->except(['store', 'destroy']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
