@extends('layouts.base')

@section('content')
<div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-dashboard-line fs-2"></i>
  <p class="fs-3 m-0">Beranda</p>
</div>
<div class="breadcrumbs-box rounded rounded-2 bg-white p-2 mt-2">
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
      <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby</li>
      <li class="breadcrumb-item active" aria-current="page">Beranda</li>
    </ol>
  </nav>
</div>
<div class="content-box mt-3 rounded rounded-2 bg-white">
  <div class="content rounded rounded-2 border border-1 p-3">
    @if (auth()->user()->role === 'super_admin')
    <div class="row row-1 justify-content-between row-gap-2">
      <div class="wrapper col-sm-4">
        <div class="card card-1  rounded-3 h-100">
          <div class="head p-2 d-flex align-items-center gap-2 letter"><i
              class='bx bx-money-withdraw p-2 rounded-circle icon-head'></i> <span>Total Invoice Terbayar</span></div>
          <div class="content-text px-2 mt-3">
            <p data-purecounter-start="0" data-purecounter-end="{{$paidInvoice}}" class="text m-0">
              Rp{{number_format($paidInvoice,0, ".", ".")}}
            </p>
          </div>
        </div>
      </div>
      <div class="wrapper col-sm-4">
        <div class="card card-2 rounded-3 h-100">
          <div class="head p-2 d-flex align-items-center gap-2 letter"><i
              class='bx bxs-package p-2 rounded-circle icon-head'></i>
            <span>Total Invoice Belum Terbayar</span>
          </div>
          <div class="content-text px-2 mt-3 ">
            <p data-purecounter-start="0" data-purecounter-end="{{$unpaidInvoice}}" class="text m-0">
              Rp{{number_format($unpaidInvoice,0, ".", ".")}}
            </p>
          </div>
        </div>
      </div>
      <div class="wrapper col-sm-4">
        <div class="card card-3 rounded-3 h-100">
          <div class="head p-2 d-flex align-items-center gap-2 letter"><i
              class='icon-head bx bx-shopping-bag p-2 rounded-circle'></i> <span>Pesanan Belum Diproses</span></div>
          <div class="content-text px-2 mt-3 ">
            <p data-purecounter-start="0" data-purecounter-duration="1" data-purecounter-end=""
              class="text m-0 purecounter">{{$unprocessedCount}}
            </p>
          </div>
        </div>
      </div>

    </div>

    <div class="row row-2 mt-3">
      <div class="wrapper col-12 col-md-6">
        <div class="table-wrapper-custom card overflow-auto col-12">
          <div class="card-header Text-secondary">5 Produk Teratas</div>
          <div class="card-body p-0">
            <table class="table table-hover table-borderless">
              <thead>
                <tr>
                  <th class="text-secondary">Peringkat</th>
                  <th class="text-secondary">Nama</th>
                  <th class="text-secondary">Qty</th>
                  <th class="text-secondary">Nilai</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($highPerfomanceProducts as $product)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$product->productName}}</td>
                  <td>{{$product->totalQty}}</td>
                  <td>Rp{{number_format($product->totalValue, 0, '.', '.')}}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="5">Product not found</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="wrapper col-12 col-md-6">
        <div class="table-wrapper-custom card overflow-auto col-12">
          <div class="card-header Text-secondary">5 Produk Paling Terbawah</div>
          <div class="card-body p-0">
            <table class="table table-hover table-borderless">
              <thead>
                <tr>
                  <th class="text-secondary">Peringkat</th>
                  <th class="text-secondary">Nama</th>
                  <th class="text-secondary">Qty</th>
                  <th class="text-secondary">Nilai</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($lowPerfomanceProducts as $product)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$product->productName}}</td>
                  <td>{{$product->totalQty}}</td>
                  <td>Rp{{number_format($product->totalValue, 0, '.', '.')}}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="5">Product not found</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    @endif

    @if (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
    <div class="row row-2 mt-3">
      <div class="wrapper col-12">
        <div class="table-wrapper-custom card h-100 overflow-auto col-12">
          <div class="card-header Text-secondary">Daftar pesanan belum selesai diproses</div>
          <div class="card-body p-3">
            <table id="example" class="table table-hover table-borderless">
              <thead>
                <tr>
                  <th class="text-secondary">No</th>
                  <th class="text-secondary">Tanggal Transaksi</th>
                  <th class="text-secondary">Kode Transaksi</th>
                  <th class="text-secondary">Nama Perusahaan</th>
                  <th class="text-secondary">Nama Barang</th>
                  <th class="text-secondary">Status Proses</th>
                  <th class="text-secondary">Qty</th>
                  <th class="text-secondary">Sub Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($unfinishedItem as $item)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$item->transaction->created_at->format('d-m-Y')}}</td>
                  <td>#{{$item->transaction->transaction_code}}</td>
                  <td>{{$item->transaction->company->name}}</td>
                  <td>{{$item->product->name}}</td>
                  <td>{{$item->process_status}}</td>
                  <td>{{$item->qty}}</td>
                  <td>Rp{{number_format($item->price * $item->qty, 0, '.', '.')}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    @endif

    <div class="row mt-3 row-gap-2 row-cols-1 row-cols-md-2">
      @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
      <a href={{route('data.product')}} class="card-dashboard text-decoration-none">
        <div class="card h-100">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Data Produk</h3>
              <p class="card-text text-secondary fw-normal">Fitur ini digunakan untuk mengolah data produk seperti
                menambah, memperbarui, atau menghapus data produk.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu">
              <i class="fs-1 ri-instance-line "></i>
            </div>
          </div>
        </div>
      </a>
      @endif
      <a href={{route('data.transaksi')}} class="card-dashboard text-decoration-none">
        <div class="card h-100">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Data Transaksi</h3>
              <p class="card-text text-secondary fw-normal">Fitur ini digunakan untuk mengolah data transaksi seperti
                memperbarui data transaksi.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu"><i
                class="fs-1 ri-arrow-left-right-line me-2"></i>
            </div>
          </div>
        </div>
      </a>
      <a href={{route('order.product')}} class="card-dashboard text-decoration-none">
        <div class="card h-100">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Pemesanan Produk</h3>
              <p class="card-text text-secondary fw-normal">Pemesanan produk merupakan fitur yang digunakan untuk
                melakukan pemesanan produk.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu"> <i class="fs-1 ri-survey-line"></i>
            </div>
          </div>
        </div>
      </a>
      @if(auth()->user()->role === 'super_admin')
      <a href={{route('data.pelanggan')}} class="card-dashboard text-decoration-none">
        <div class="card h-100">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Data Pelanggan</h3>
              <p class="card-text text-secondary fw-normal">Fitur ini digunakan untuk mengolah data pelanggan seperti
                menambah, menghapus, memperbarui data pelanggan.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu"> <i class="fs-1 ri-team-line"></i>
            </div>
          </div>
        </div>
      </a>
      @endif
      @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'super_admin_cust')
      <a href="{{route('data.admin')}}" class="card-dashboard text-decoration-none">
        <div class="card h-100">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Data Admin</h3>
              <p class="card-text text-secondary fw-normal">Fitur ini digunakan untuk mengolah data admin seperti
                menambah, menghapus, memperbarui data admin.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu"> <i class="fs-1 ri-admin-line"></i>
            </div>
          </div>
        </div>
      </a>
      @endif
      <a href="{{route('data-invoice.index')}}" class="card-dashboard text-decoration-none">
        <div class="card h-100">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Data Invoice</h3>
              <p class="card-text text-secondary fw-normal">Fitur ini digunakan untuk mengolah data invoice.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu"> <i class="fs-1 ri-article-fill"></i>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>
@endsection