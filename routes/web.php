<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SubscriberController;

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

Route::get('/', [SiteController::class, 'index']);

Route::prefix('/subscribers')->group(function () {
    Route::get('/get-all', [SubscriberController::class, 'get_all']);
    Route::get('/get/{id}', [SubscriberController::class, 'get_single']);
    Route::post('/new', [SubscriberController::class, 'new']);
    Route::post('/update', [SubscriberController::class, 'update']);
    Route::delete('/delete/{id}', [SubscriberController::class, 'delete']);
});

Route::prefix('/contacts')->group(function () {
    Route::get('/get/{id}', [ContactController::class, 'get']);
    Route::post('/make-primary', [ContactController::class, 'make_primary']);
    Route::post('/new', [ContactController::class, 'new']);
    Route::delete('/delete/{id}', [ContactController::class, 'delete']);
});
