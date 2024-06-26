@extends('layouts.base')

@section('content')
<div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-article-fill fs-2"></i>
  <p class="fs-3 m-0">Data Invoice</p>
</div>
<div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
      <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby</li>
      <li class="breadcrumb-item active" aria-current="page">Data Invoice</li>
    </ol>
  </nav>
</div>
<div class="content-box mt-3 rounded rounded-2 bg-white">
  <div class="content rounded rounded-2 border border-1 p-3">
    <div class="btn-wrapper mt-2">
      {{-- Error Alert --}}
      @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{$error}}</li>
          @endforeach
        </ul>
      </div>
      @endif
      @if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin')
      <a href="{{route('data-invoice.create')}}" class="btn btn-success"><i class="ri-add-box-line me-2"></i>Tambah
        Invoice</a>
      @endif
    </div>
    <form action="{{route('data-invoice.index')}}" class="mt-3" method="get">
      <div class="filter-wrapper row align-items-end">
        <div class="form-group col-12 mt-2 mt-xl-0 mt-2 mt-xl-0 col-xl-1">
          <label class="mb-1 text-left">Tampilkan :</label>
          <select class="form-select" name="pagination">
            <option value="10" @selected(request()->get('pagination') == 10)>10</option>
            <option value="25" @selected(request()->get('pagination') == 25)>25</option>
            <option value="50" @selected(request()->get('pagination') == 50)>50</option>
            <option value="100" @selected(request()->get('pagination') == 100)>100</option>
          </select>
        </div>
        <div class="form-group col-12 mt-2 mt-xl-0 mt-2 mt-xl-0 col-lg-2">
          <label for="keyword" class="mb-1 text-left">Kode Invoice :</label>
          <div class="input-group">
            <input class="form-control" type="text" name="search_value" placeholder="Masukkan kode invoice"
              value="{{request()->get('search_value')}}" />
          </div>
        </div>
        <div class="form-group col-12 mt-2 mt-xl-0 mt-2 mt-xl-0 col-lg-2">
          <label for="keyword" class="mb-1 text-left">Status Pelunasan :</label>
          <div class="input-group">
            <select class="form-select" name="payment_status">
              <option value="" @selected(request()->get('payment_status') == '') >semua</option>
              <option value="0" @selected(request()->get('payment_status') == '0')>Unpaid</option>
              <option value="1" @selected(request()->get('payment_status') == '1')>Paid</option>
            </select>
          </div>
        </div>
        @if (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
        <div class="form-group col-12 mt-2 mt-xl-0 mt-2 mt-xl-0 col-lg-2">
          <label for="keyword" class="mb-1 text-left">Status Dp :</label>
          <div class="input-group">
            <select class="form-select" name="dp_status">
              <option value="" @selected(request()->get('dp_status') == '')>semua</option>
              <option value="0" @selected(request()->get('dp_status') == '0')>Unpaid</option>
              <option value="1" @selected(request()->get('dp_status') == '1')>Paid</option>
            </select>
          </div>
        </div>
        <div class="col-12 mt-2 mt-xl-0 mt-2 mt-xl-0 col-lg-2">
          <button class="btn btn-success" type="submit">Apply</button>
        </div>
        @endif
      </div>
    </form>
    <div class="table-wrapper mt-2 mb-2">
      <table class="table mt-3 table-hover table-borderless" style="width: 100%">
        <thead>
          <tr>
            <th class="text-secondary">ID</th>
            <th class="text-secondary">Kode Invoice</th>
            <th class="text-secondary">Perusahaan</th>
            <th class="text-secondary">Nominal</th>
            <th class="text-secondary">Jatuh Tempo</th>
            <th class="text-secondary">Status Pelunasan</th>
            <th class="text-secondary">Nominal DP</th>
            <th class="text-secondary">Jatuh Tempo DP</th>
            <th class="text-secondary">Status Dp</th>
            <th class="text-secondary">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @foreach ($invoices as $invoice)
          <tr>
            <td>{{$invoice->id }}</td>
            <td>#{{$invoice->invoice_code }}</td>
            <td>{{$invoice->company->name }}</td>
            <td>Rp{{number_format($invoice->amount,0, ".", ".")}}</td>
            <td>{{date("Y-m-d", strtotime($invoice->payment_due_date)) }}</td>
            <td>{{$invoice->payment_status == 1 ? 'Paid' : 'Unpaid'}}</td>
            <td>Rp{{number_format($invoice->dp_value,0, ".", ".")}}</td>
            <td>{{ $invoice->dp_value > 0 ? ($invoice->dp_due_date->format('d-m-Y')) : '-' }}</td>
            <td>{{$invoice->dp_value > 0 ? ($invoice->dp_status ? 'Paid' : 'Unpaid') : '-'}}</td>
            <td class="">
              <div class="btn-group">
                <button type="button" class="btn btn-light  dropdown-toggle" data-bs-toggle="dropdown"
                  data-bs-auto-close="outside" aria-expanded="false">
                  Aksi
                </button>
                <ul class="dropdown-menu dropdown-menu-end px-2">
                  <li>
                    <a href="{{route('data-invoice.show', $invoice->id)}}" class="dropdown-item text-dropdown rounded-2"
                      type="button">
                      Detail Invoice
                    </a>
                  </li>
                  @if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin')
                  <li>
                    <div class="btn-group dropstart dropdown-item">
                      <div type="button" class=" dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        Ubah Status Pelunasan
                      </div>
                      <form
                        action="{{route('data-invoice.changePaymentStatus.update', ['id' => $invoice->id, 'payment_status' => (!$invoice->payment_status)])}}"
                        method="POST" class="mb-0">
                        @csrf
                        @method('PUT')
                        <ul class="dropdown-menu">
                          <li>
                            @if (!$invoice->payment_status)
                            <div class="dropdown-item text-start text-dropdown rounded-2 active cursor-default">
                              Unpaid
                            </div>
                            @else
                            <button type="submit" class="text-decoration-none btn p-0 w-100">
                              <div class="dropdown-item text-start text-dropdown rounded-2" type="button">
                                Unpaid
                              </div>
                            </button>
                            @endif
                          </li>
                          <li>
                            @if ($invoice->payment_status)
                            <div class="dropdown-item text-start text-dropdown rounded-2 active cursor-default">
                              Paid
                            </div>
                            @else
                            <button type="submit" class="text-decoration-none btn p-0 w-100">
                              <div class="dropdown-item text-start text-dropdown rounded-2" type="button">
                                Paid
                              </div>
                            </button>
                            @endif
                          </li>
                        </ul>
                      </form>
                    </div>
                  </li>
                  @if ($invoice->dp_value > 0)
                  <li>
                    <div class="btn-group dropstart dropdown-item">
                      <div type="button" class=" dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        Ubah Status Dp
                      </div>
                      <form
                        action="{{route('data-invoice.changeDpStatus.update', ['id' => $invoice->id, 'dp_status' => (!$invoice->dp_status)])}}"
                        method="POST" class="mb-0">
                        @csrf
                        @method('PUT')
                        <ul class="dropdown-menu">
                          <li>
                            @if (!$invoice->dp_status)
                            <div class="dropdown-item text-start text-dropdown rounded-2 active cursor-default">
                              Unpaid
                            </div>
                            @else
                            <button type="submit" class="text-decoration-none btn p-0 w-100">
                              <div class="dropdown-item text-start text-dropdown rounded-2" type="button">
                                Unpaid
                              </div>
                            </button>
                            @endif
                          </li>
                          <li>
                            @if ($invoice->dp_status)
                            <div class="dropdown-item text-start text-dropdown rounded-2 active cursor-default">
                              Paid
                            </div>
                            @else
                            <button type="submit" class="text-decoration-none btn p-0 w-100">
                              <div class="dropdown-item text-start text-dropdown rounded-2" type="button">
                                Paid
                              </div>
                            </button>
                            @endif
                          </li>
                      </form>
                    </div>
                  </li>
                  @endif
                  @endif
                  <li>
                    <a href="{{route('data-invoice.downloadPdf', $invoice->invoice_code)}}"
                      class="dropdown-item text-dropdown rounded-2" type="button">
                      Download Invoice
                    </a>
                  </li>
                  <li>
                    <a target="blank" href="{{route('data-invoice.viewPdf', $invoice->invoice_code)}}"
                      class="dropdown-item text-dropdown rounded-2" type="button">
                      View Invoice
                    </a>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="pagination-box d-flex justify-content-end">
      {{$invoices->links('pagination::simple-bootstrap-5')}}
    </div>
  </div>
</div>


@endsection