<?php

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('msallogin', [App\Http\Controllers\LoginController::class, 'msalLogin'])->name('msallogin');

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('autocomplete')->group(function() {
        Route::post('binlocation', [App\Http\Controllers\AutocompleteController::class, 'autocompleteBinLocation']);
        Route::post('productcode', [App\Http\Controllers\AutocompleteController::class, 'autocompleteProductCode']);
    });

    Route::prefix('inventory')->group(function() {
        Route::resource('negativequantities', \App\Http\Controllers\IN_NegativeQuantitiesController::class, ['as' => 'inventory']);
        Route::resource('variancereport', \App\Http\Controllers\IN_VarianceReportController::class, ['as' => 'inventory']);
        Route::resource('view', \App\Http\Controllers\IN_ViewByController::class, ['as' => 'inventory']);
    });


    Route::patch('userprofile', [App\Http\Controllers\UpdateUserController::class, 'update']);
});
