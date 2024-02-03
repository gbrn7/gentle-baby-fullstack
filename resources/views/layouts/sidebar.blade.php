<div class="sidebar" id="side_nav">
  <div class="header-box px-2 pt-5 pb-2 d-flex justify-content-center">
    <h1 class="header-text bg-dark rounded rounded-3 p-2 border border-1">
      <span class="me-2 text-white"><i class="ri-apps-line"></i>
        Gentle Baby
      </span>
    </h1>
  </div>
  <div class="list-box  d-flex flex-column">
    <ul class="list-unstyled px-3 pt-3 d-flex flex-column gap-2">
      <li class="rounded {{Request::segment(2) === 'home-page' ? 'active' : ''}} rounded-2">
        <a href={{route('client')}} class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-dashboard-line me-2"></i>Beranda</a>
      </li>
      @if (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
      <li class="rounded {{Request::segment(2) === 'data-product' ? 'active' : ''}} rounded-2">
        <a href="{{route('data.product')}}"
          class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-instance-line me-2"></i>Data Produk</a>
      </li>
      @endif
      <li class="rounded {{Request::segment(2) === 'data-transaksi' ? 'active' : ''}} rounded-2">
        <a href={{route('data.transaksi')}}
          class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-arrow-left-right-line me-2"></i><span class="nav-item-label">Data
            Transaksi</span></a>
      </li>
      <li class="rounded {{Request::segment(2) === 'order-product' ? 'active' : ''}} rounded-2">
        <a href={{route('order.product')}}
          class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-survey-line me-2"></i>Pemesanan Produk</a>
      </li>
      @if(auth()->user()->role === 'super_admin')
      <li class="rounded {{Request::segment(2) === 'data-pelanggan' ? 'active' : ''}} rounded-2">
        <a href={{route('data.pelanggan')}}
          class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-team-line me-2"></i>Data Perusahaan</a>
      </li>
      @endif
      @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'super_admin_cust')
      <li class="rounded {{Request::segment(2) === 'data-admin' ? 'active' : ''}} rounded-2">
        <a href={{route('data.admin')}}
          class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-admin-line me-2"></i>Data Admin</a>
      </li>
      @endif
    </ul>
  </div>
</div>