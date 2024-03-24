<?php

use App\Http\Controllers\PrizesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// PREDEFINED
Route::resource('prizes', PrizesController::class);


// PREDEFINED
Route::get('/', function () {
    return redirect()->route('prizes.index');
});

// PREDEFINED
Route::post('/simulate', '\App\Http\Controllers\PrizesController@simulate')->name('simulate');

// PREDEFINED
Route::post('/reset', '\App\Http\Controllers\PrizesController@reset')->name('reset');
