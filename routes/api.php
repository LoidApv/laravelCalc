<?php

use App\Http\Controllers\CalculationController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(CalculationController::class)
    ->prefix('/calculation')
    ->middleware(['web'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->group(function () {
        Route::get('/', 'page');
        Route::get('/history', 'history');
        Route::delete('/history', 'deleteHistory');
        Route::patch('/leftValue', 'setLeftValue');
        Route::patch('/rightValue', 'setRightValue');
        Route::patch('/operation', 'setOperation');
        Route::get('/getResult', 'getResult');
    });
