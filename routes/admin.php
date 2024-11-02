<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BusinessController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function(){
Route::get('/login',[AuthController::class,'login']);
Route::post('/login',[AuthController::class,'loginStore']);
});

Route::middleware('auth:admin')->group(function(){
Route::get('/dashboard',[AuthController::class,'dashboard']);
Route::resource('/business',BusinessController::class);
Route::get('/business/{id}/login',[BusinessController::class,'show'])->name('business.login');

});
