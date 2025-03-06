<?php

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

// API rotaları
Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum']], function () {
    // Teklifler
    Route::apiResource('offers', 'App\Http\Controllers\Api\OfferController', ['as' => 'api']);
    Route::post('offers/bulk-delete', 'App\Http\Controllers\Api\OfferController@bulkDelete');
    
    // Şirketler
    Route::apiResource('companies', 'App\Http\Controllers\Api\CompanyController', ['as' => 'api']);
    Route::post('companies/{company}/status', 'App\Http\Controllers\Api\CompanyController@updateStatus');
    
    // Acenteler
    Route::apiResource('agencies', 'App\Http\Controllers\Api\AgencyController', ['as' => 'api']);
    Route::post('agencies/{agency}/status', 'App\Http\Controllers\Api\AgencyController@updateStatus');
    
    // İstatistikler
    Route::get('dashboard/stats', 'App\Http\Controllers\Api\DashboardController@getStats');
});

// Public API rotaları
Route::group(['prefix' => 'v1/public'], function () {
    Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
    Route::post('register', 'App\Http\Controllers\Api\AuthController@register');
    Route::post('forgot-password', 'App\Http\Controllers\Api\AuthController@forgotPassword');
}); 