<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DataAdminController;

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


Route::get('/sign-in', [AuthController::class, 'index'])->name('sign-in');
Route::post('/sign-in', [AuthController::class, 'authenticate'])->name('sign-in.auth');

Route::group(['prefix'=>'client', 'middleware' => ['auth']], function(){
  Route::get('/home-page', [ClientController::class, 'index'])->name('client');
  
  Route::get('/data-admin', [DataAdminController::class, 'index'])->name('data.admin');

  Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::any('/{any}', function () {
  if(auth()->user()){
    return redirect()->route('client');
}
return redirect()->route('sign-in');
})->where('any', '.*');
