<?php

use App\Http\Controllers\Api\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('API')->name('api.')->group(function () {
    Route::prefix('patients')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index_patients');
        Route::post('/', [PatientController::class, 'store'])->name('store_patients');
        Route::get('/{id}', [PatientController::class, 'show'])->name('show_patients');
        Route::put('/{id}', [PatientController::class, 'edit'])->name('edit_patients');
        Route::delete('/{id}', [PatientController::class, 'destroy'])->name('destroy_patients');
    });
});
