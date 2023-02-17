<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\ProfileDataController; 
/*
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

Route::get('/', function () {
    return redirect()->route('admin.users.index');
})->name('home');


Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);

Route::get('/send-sms', [SmsController::class,'sendSms'])->name('send-sms');
Route::get('/verify-sms', [SmsController::class,'verifySms'])->name('verify-sms');
Route::get('/check-form',[SmsController::class,'smsForm'])->name('check-form');
Route::post('/check-sms', [SmsController::class,'checkSms'])->name('check-sms');

//middleware(['jwt.auth'])-> not supported in laravel9  
Route::prefix('admin/users')->controller(UserController::class)->middleware(['web'])->group(function(){
    Route::get('/', 'index')->name('admin.users.index');
    Route::get('/create', 'create')->name('admin.users.create');
    Route::get('/{user}','show')->name('admin.users.show');
    Route::get('/edit/{user}/edit', 'edit')->name('admin.users.edit');
    Route::put('/{user}', 'update')->name('admin.users.update');
    Route::delete('/{user}','delete')->name('admin.users.destroy');
    Route::post('/','store')->name('admin.users.store');
  });
