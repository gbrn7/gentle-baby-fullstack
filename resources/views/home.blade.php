@extends('layouts.base')

@section('content')
<div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-dashboard-line fs-2"></i>
  <p class="fs-3 m-0">Beranda</p>
</div>
<div class="breadcrumbs-box rounded rounded-2 bg-white p-2 mt-2">
  <nav
    style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
    aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
      <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Baby Gentle</li>
      <li class="breadcrumb-item active" aria-current="page">Beranda</li>
    </ol>
  </nav>
</div>
<div class="content-box p-3 mt-3 rounded rounded-2 bg-white">
  <div class="content rounded rounded-2 border border-1 p-3">
    <div class="row row-gap-3">
      <a href={{route('data.product')}} class="col-sm-6 card-dashboard text-decoration-none">
        <div class="card ">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Data Produk</h3>
              <p class="card-text text-secondary fw-light">Fitur ini digunakan untuk mengolah data produk seperti
                menambah, memperbarui, atau menghapus data produk.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu">
              <i class="fs-1 ri-instance-line "></i>
            </div>
          </div>
        </div>
      </a>
      <a href={{route('data.transaksi')}} class="col-sm-6 card-dashboard text-decoration-none">
        <div class="card ">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Data Transaksi</h3>
              <p class="card-text text-secondary fw-light">Fitur ini digunakan untuk mengolah data transaksi seperti
                memperbarui data transaksi.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu"><i
                class="fs-1 ri-arrow-left-right-line me-2"></i>
            </div>
          </div>
        </div>
      </a>
      <a href={{route('order.product')}} class="col-sm-6 card-dashboard text-decoration-none">
        <div class="card ">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Pemesanan Produk</h3>
              <p class="card-text text-secondary fw-light">Pemesanan produk merupakan fitur yang digunakan untuk
                melakukan pemesanan produk.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu"> <i class="fs-1 ri-survey-line"></i>
            </div>
          </div>
        </div>
      </a>
      @if(auth()->user()->role === 'super_admin')
      <a href={{route('data.pelanggan')}} class="col-sm-6 card-dashboard text-decoration-none">
        <div class="card ">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Data Pelanggan</h3>
              <p class="card-text text-secondary fw-light">Fitur ini digunakan untuk mengolah data pelanggan seperti
                menambah, menghapus, memperbarui data pelanggan.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu"> <i class="fs-1 ri-team-line"></i>
            </div>
          </div>
        </div>
      </a>
      <a href="{{route('data.admin')}}" class="col-sm-6 card-dashboard text-decoration-none">
        <div class="card ">
          <div class="card-body  row justify-content-between align-items-center">
            <div class="card-body-content col-9">
              <h3 class="card-title">Data Admin</h3>
              <p class="card-text text-secondary fw-light">Fitur ini digunakan untuk mengolah data admin seperti
                menambah, menghapus, memperbarui data admin.</p>
            </div>
            <div class="col-2 col-sm-3 d-flex justify-content-center img-menu"> <i class="fs-1 ri-admin-line"></i>
            </div>
          </div>
        </div>
      </a>
      @endif
    </div>
  </div>
</div>
@endsection