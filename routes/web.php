<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DataAdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Livewire\DataTransaksi;
use App\Livewire\DataDetailTransaksi;
use App\Livewire\OrderProduct;
use Livewire\Livewire;

// use Livewire\Livewire;

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

Route::group(['prefix' => 'client', 'middleware' => ['auth']], function () {
  Route::get('/home-page', [ClientController::class, 'index'])->name('client');
  Route::get('/getCurrentUser', [ClientController::class, 'getCurrentUser'])->name('client.getCurrentUser');
  Route::get('/setModeSession', [ClientController::class, 'setModeSession'])->name('client.setModeSession');
  Route::put('/updateCurrentUserData', [ClientController::class, 'update'])->name('client.currentUser.update');

  Route::prefix('/data-admin')->group(function () {
    Route::get('/', [DataAdminController::class, 'index'])->name('data.admin');
    Route::post('/store', [DataAdminController::class, 'store'])->name('data.admin.store');
    Route::get('/getforms', [DataAdminController::class, 'getForm'])->name('data.admin.getForm');
    Route::put('/update', [DataAdminController::class, 'update'])->name('data.admin.update');
    Route::delete('/destroy', [DataAdminController::class, 'delete'])->name('data.admin.delete');
  });

  Route::prefix('/data-pelanggan')->group(function () {
    Route::get('/my-company', [CompanyController::class, 'getCurrentCompany'])->name('data.perusahaan.current');
    Route::put('/my-company/update', [CompanyController::class, 'updateMyCompany'])->name('data.perusahaan.update');

    Route::get('/', [CompanyController::class, 'index'])->name('data.pelanggan');
    Route::post('/store', [CompanyController::class, 'store'])->name('data.pelanggan.store');
    Route::get('/getforms', [CompanyController::class, 'getForm'])->name('data.pelanggan.getForm');
    Route::put('/update', [CompanyController::class, 'update'])->name('data.pelanggan.update');
    Route::get('/{id}', [CompanyController::class, 'detailCompany'])->name('data.admin.pelanggan');

    Route::prefix('/data-admin')->group(function () {
      Route::post('/store', [CompanyController::class, 'storeAdmin'])->name('data.admin.pelanggan.store');
      Route::get('/getforms', [CompanyController::class, 'getFormAdmin'])->name('data.admin.pelanggan.getForm');
      Route::put('/update', [CompanyController::class, 'updateAdmin'])->name('data.admin.pelanggan.update');
      Route::delete('/destroy', [CompanyController::class, 'deleteAdmin'])->name('data.admin.pelanggan.delete');
    });
  });

  Route::group(['prefix' => '/data-product', 'middleware' => ['admin.auth']], function () {
    Route::get('/', [ProductController::class, 'index'])->name('data.product');
    Route::get('/create', [ProductController::class, 'createProduct'])->name('data.product.create');
    Route::post('/store', [ProductController::class, 'store'])->name('data.product.store');
    Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('data.product.edit');
    Route::put('/update/{id}', [ProductController::class, 'update'])->name('data.product.update');
    Route::delete('/destroy', [ProductController::class, 'delete'])->name('data.product.delete');
    Route::delete('/destroyImage', [ProductController::class, 'deleteThumbnail'])->name('data.product.delete.thumbnail');
  });

  Route::prefix('/data-transaksi')->group(function () {
    Route::get('/', DataTransaksi::class)->name('data.transaksi');
    Route::put('/edit', [DataTransaksi::class, 'updateStatus'])->name('data.transaksi.update.status');
    Route::get('/view/pdf/{id}', [DataTransaksi::class, 'viewPDF'])->name('data.transaksi.viewPDF');
    Route::get('/{id}', DataDetailTransaksi::class)->name('data.transaksi.detail');
    Route::put('/detailTransaksi/changeStatus/{id}', [DataDetailTransaksi::class, 'changeStatus'])->name('data.transaksi.detail.changeStatus.update');
    Route::put('/detailTransaksi/{id}', [DataDetailTransaksi::class, 'updateTransaction'])->name('data.transaksi.detail.update');
  });

  Route::resource('data-invoice', InvoiceController::class);
  Route::get('/data-invoice/{invoiceCode}/downloadPdf', [InvoiceController::class, 'downloadPdf'])->name('data-invoice.downloadPdf');
  Route::get('/data-invoice/{invoiceCode}/viewPdf', [InvoiceController::class, 'viewPdf'])->name('data-invoice.viewPdf');
  Route::put('/data-invoice/{id}/changePaymentStatus', [InvoiceController::class, 'changePaymentStatus'])->name('data-invoice.changePaymentStatus.update');
  Route::put('/data-invoice/{id}/changeDpStatus', [InvoiceController::class, 'changeDpStatus'])->name('data-invoice.changeDpStatus.update');


  Route::prefix('/order-product')->group(function () {
    Route::get('/', OrderProduct::class)->name('order.product');
  });

  Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});


Livewire::setUpdateRoute(function ($handle) {
  return Route::post('/gentleliving/gentlebaby/livewire/update', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
  return Route::post('/gentleliving/gentlebaby/livewire/livewire.js', $handle);
});

Route::any('/{any}', function () {
  if (auth()->user()) {
    return redirect()->route('client');
  }
  return redirect()->route('sign-in');
})->where('any', '.*');
