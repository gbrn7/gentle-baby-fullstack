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
  Route::get('/getCurrentUser', [ClientController::class, 'getCurrentUser'])->name('client.getCurrentUser');
  
  Route::prefix('/data-admin')->group(function () {
    Route::get('/', [DataAdminController::class, 'index'])->name('data.admin');
    Route::post('/store', [DataAdminController::class, 'store'])->name('data.admin.store');
    Route::get('/getforms', [DataAdminController::class, 'getForm'])->name('data.admin.getForm');
    Route::put('/update', [DataAdminController::class, 'update'])->name('data.admin.update');
    Route::delete('/destroy', [DataAdminController::class, 'delete'])->name('data.admin.delete');
  });

  Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::any('/{any}', function () {
  if(auth()->user()){
    return redirect()->route('client');
}
return redirect()->route('sign-in');
})->where('any', '.*');
