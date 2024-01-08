<div class="sidebar" id="side_nav">
  <div class="header-box px-2 pt-5 pb-2 d-flex justify-content-center">
    <h1 class="header-text rounded rounded-3 p-2 border border-1">
      <span class="me-2 text-white"><i class="ri-apps-line"></i>
        Baby Gentle
      </span>
    </h1>
  </div>
  <div class="list-box  d-flex flex-column justify-content-between gap-5">
    <ul class="list-unstyled px-3 pt-3 d-flex flex-column gap-2">
      <li class="rounded {{Request::segment(2) === 'home-page' ? 'active' : ''}} rounded-2">
        <a href={{route('client')}} class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-dashboard-line me-2"></i>Beranda</a>
      </li>
      <li class="rounded {{Request::segment(2) === 'criteria' ? 'active' : ''}} rounded-2">
        <a href="#" class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-instance-line me-2"></i>Data Produk</a>
      </li>
      <li class="rounded {{Request::segment(2) === 'alternatives' ? 'active' : ''}} rounded-2">
        <a href="#" class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-arrow-left-right-line me-2"></i>Data Transaksi</a>
      </li>
      <li class="rounded {{Request::segment(2) === 'grades' ? 'active' : ''}} rounded-2">
        <a href="#" class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-survey-line me-2"></i>Pemesanan Produk</a>
      </li>
      <li class="rounded {{Request::segment(2) === 'grades' ? 'active' : ''}} rounded-2">
        <a href="#" class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-team-line me-2"></i>Data Pelanggan</a>
      </li>
      @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'super_admin_cust')
      <li class="rounded {{Request::segment(2) === 'data-admin' ? 'active' : ''}} rounded-2">
        <a href={{route('data.admin')}}
          class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-admin-line me-2"></i>Data Admin</a>
      </li>
      @endif
      <li class="rounded {{Request::segment(2) === 'results' ? 'active' : ''}} rounded-2">
        <a href="#" class="text-decoration-none p-3 rounded rounded-2 d-flex align-items-baseline"><i
            class="ri-profile-line me-2"></i></i>Pengaturan Profil</a>
      </li>
    </ul>
  </div>

  <hr class="h-color mx-3" />
</div>