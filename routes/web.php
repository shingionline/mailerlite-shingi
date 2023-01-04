<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\FieldController;
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

Route::prefix('/fields')->group(function () {
    Route::get('/get', [FieldController::class, 'get_all']);
    Route::delete('/delete/{id}', [FieldController::class, 'delete']);
    Route::post('/new', [FieldController::class, 'new']);
});
