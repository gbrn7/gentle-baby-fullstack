@extends('layouts.base')

@section('content')
<div>
  <div>
    <div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-arrow-left-right-line fs-2"></i>
      <p class="fs-3 m-0">Data Detail Invoice #{{$invoice->invoice_code}}</p>
    </div>
    <div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby
          </li>
          <li class="breadcrumb-item"><a href={{route('data-invoice.index')}} class="text-decoration-none">Data
              Invoice</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Detail Transaksi
            {{$invoice->invoice_code}}
          </li>
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
        </div>

        @switch(auth()->user()->role)
        @case('super_admin')
        <div class="info-wrapper row">
          <div class="col-12 col-sm-4">
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Nama Perusahaan</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->company->name}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Hpp</div>
              <div class="card-body">
                <p class="card-title fw-bold">Rp{{number_format($totalHpp,0,".",".")}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Cashback Item Qty</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$totalQtyCashback}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Jatuh Tempo DP</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->dp_value > 0 ? ($invoice->dp_due_date->format('d-m-Y')) :
                  '-'}}</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-4">
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Tanggal Transaksi</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->created_at->format('d-m-Y')}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Profit</div>
              <div class="card-body">
                <p class="card-title fw-bold">Rp{{number_format($totalProfit,0,".",".")}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Jatuh Tempo Pelunasan</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->payment_due_date->format('d-m-Y')}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Status Peluanasan</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->payment_status ? 'Terbayar' :
                  'Belum Terbayar'}}</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-4">
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Revenue</div>
              <div class="card-body">
                <p class="card-title fw-bold">Rp{{number_format($invoice->amount,0,".", ".")}}
                </p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Nilai Cashback</div>
              <div class="card-body">
                <p class="card-title fw-bold">Rp{{number_format($totalCashback,0,".",".")}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Nominal DP</div>
              <div class="card-body">
                <p class="card-title fw-bold">Rp{{number_format($invoice->dp_value,0,".",".")}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Status DP</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->dp_value > 0 ? ($invoice->dp_status ? 'Terbayar' : 'Belum
                  Terbayar') :
                  '-'}}</p>
              </div>
            </div>
          </div>
        </div>
        @break

        @case('admin' || 'super_admin_cust' || 'admin_cust')
        <div class="info-wrapper row">
          <div class="col-12 col-sm-4">
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Nama Perusahaan</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->company->name}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Nominal DP</div>
              <div class="card-body">
                <p class="card-title fw-bold">Rp{{number_format($invoice->dp_value,0,".",".")}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Status Pelunasan</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->payment_status ? 'Terbayar' :
                  'Belum Terbayar'}}</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-4">
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Tanggal Transaksi</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->created_at->format('d-m-Y')}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Jatuh Tempo Pelunasan</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->payment_due_date->format('d-m-Y')}}</p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Status DP</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->dp_value > 0 ? ($invoice->dp_status ? 'Terbayar' : 'Belum
                  Terbayar') :
                  '-'}}</p>
              </div>
            </div>

          </div>
          <div class="col-12 col-sm-4">
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">{{auth()->user()->role == 'admin' ? 'Revenue' : 'Tagihan'}}</div>
              <div class="card-body">
                <p class="card-title fw-bold">Rp{{number_format($invoice->amount,0,
                  ".", ".")}}
                </p>
              </div>
            </div>
            <div class="card bg-glass mb-3">
              <div class="card-header text-secondary">Jatuh Tempo DP</div>
              <div class="card-body">
                <p class="card-title fw-bold">{{$invoice->dp_value > 0 ? ($invoice->dp_status ? 'Terbayar' : 'Belum
                  Terbayar') :
                  '-'}}</p>
              </div>
            </div>
          </div>
        </div>
        @break

        @endswitch

        <div class="table-wrapper card bg-glass mb-5 mt-2">
          <div class="card-header text-secondary ">Rincian Produk</div>
          <div class="card-body receipt-wrapper">
            <table id="example" class="table table-sortable mt-3 table-hover table-borderless" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-secondary">ID</th>
                  <th class="text-secondary">Kode Transaksi</th>
                  <th class="text-secondary">Nama</th>
                  <th class="text-secondary">Harga</th>
                  <th class=" text-secondary">Qty</th>
                  @if (auth()->user()->role == 'super_admin' || auth()->user()->role ===
                  'admin')
                  <th class="text-secondary">hpp</th>
                  <th class=" text-secondary">Status Cashback</th>
                  <th class=" text-secondary">Nilai Cashback</th>
                  <th class=" text-secondary">Qty Cashback</th>
                  <th class=" text-secondary">Total Cashback</th>
                  @endif
                  <th class=" text-secondary">Total</th>
                </tr>
              </thead>
              <tbody id="tableBody">
                @foreach ($invoice->detailTransactions as $detailsTransaction)
                <tr>
                  <td>{{$detailsTransaction->product->id }}</td>
                  <td>#{{$detailsTransaction->transaction->transaction_code }}</td>
                  <td>{{$detailsTransaction->product->name }}</td>
                  <td>Rp{{number_format($detailsTransaction->price,0, ".",".")}}</td>
                  <td>{{$detailsTransaction->qty }}</td>
                  @if (auth()->user()->role == 'super_admin' || auth()->user()->role ==='admin')
                  <td>Rp{{number_format($detailsTransaction->hpp,0, ".",".")}}</td>
                  <td>{{$detailsTransaction->is_cashback == 1 ? 'Iya' : 'Tidak'}}</td>
                  <td>Rp{{number_format($detailsTransaction->cashback_value,0, ".",".")}}</td>
                  <td>{{$detailsTransaction->qty_cashback_item}}</td>
                  <td>{{$detailsTransaction->qty_cashback_item * $detailsTransaction->cashback_value}}</td>
                  @endif
                  <td>Rp{{number_format(($detailsTransaction->price *
                    $detailsTransaction->qty),0,".",".")}}
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection