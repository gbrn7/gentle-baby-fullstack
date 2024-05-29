<div>
    <div>
        <div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-arrow-left-right-line fs-2"></i>
            <p class="fs-3 m-0">Data Detail Transaksi #{{$transaction->transaction_code}}</p>
        </div>
        <div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby
                    </li>
                    <li class="breadcrumb-item"><a href={{route('data.transaksi')}} class="text-decoration-none">Data
                            Transaksi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Transaksi
                        {{$transaction->transaction_code}}
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
                                <p class="card-title fw-bold">{{$transaction->companyName}}</p>
                            </div>
                        </div>
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Revenue</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp{{number_format($transaction->revenue,0,
                                    ".",
                                    ".")}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Tanggal Transaksi</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->transactionDate}}</p>
                            </div>
                        </div>
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Profit</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp{{number_format($transaction->profit,0,
                                    ".",
                                    ".")}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Nilai Cashback</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp{{$transaction->cashback}}</p>
                            </div>
                        </div>
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Cashback Item</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->cashback_item}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @break

                @case('admin')
                <div class="info-wrapper row">
                    <div class="col-12 col-sm-4">
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Nama Perusahaan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->companyName}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Tanggal Transaksi</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->transactionDate}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Nominal</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp{{number_format($transaction->revenue,0,
                                    ".", ".")}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @break

                @case('super_admin_cust' || 'admin_cust' )
                <div class="info-wrapper row">
                    <div class="col-12 col-sm-4">
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Nama Perusahaan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->companyName}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card bg-glass mb-3">
                            <div class="card-header text-secondary">Tanggal Transaksi</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->transactionDate}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @break
                @endswitch

                <div class="table-wrapper card bg-glass mb-5 mt-2">
                    <div class="card-header text-secondary ">Rincian Produk</div>
                    <div class="card-body receipt-wrapper">
                        <div class="filter-wrapper">
                            <div class="form-group col-12 col-md-4">
                                <label for="name" class="mb-1 text-left">Search :</label>
                                <input class="form-control" type="text" wire:model.live.debounce.500ms="keywords" />
                            </div>
                        </div>
                        <table id="" class="table table-sortable mt-3 table-hover table-borderless" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="text-secondary sort @if ($sortColumn=='id') {{$sortDirection}}@endif"
                                        wire:click="sort('id')">ID</th>
                                    <th class="text-secondary">Nama</th>
                                    <th class="text-secondary sort @if ($sortColumn=='price') {{$sortDirection}}@endif"
                                        wire:click="sort('price')">Harga</th>
                                    <th class="text-secondary sort @if ($sortColumn=='qty') {{$sortDirection}}@endif "
                                        wire:click="sort('qty')">Qty</th>
                                    @if (auth()->user()->role == 'super_admin' || auth()->user()->role ===
                                    'admin')
                                    <th class="text-secondary sort @if ($sortColumn=='hpp') {{$sortDirection}}@endif"
                                        wire:click="sort('hpp')">hpp</th>
                                    <th class="text-secondary sort @if ($sortColumn=='is_cashback') {{$sortDirection}}@endif"
                                        wire:click="sort('is_cashback')">Status Cashback</th>
                                    <th class="text-secondary sort @if ($sortColumn=='cashback_value') {{$sortDirection}}@endif"
                                        wire:click="sort('cashback_value')">Nilai Cashback</th>
                                    <th class="text-secondary sort @if ($sortColumn=='qty_cashback_item') {{$sortDirection}}@endif"
                                        wire:click="sort('qty_cashback_item')">Total Cashback</th>
                                    @endif
                                    <th class="text-secondary">Total</th>
                                    <th class="text-secondary">Status Proses</th>
                                    <th class="text-secondary">Kode Invoice</th>
                                    @if (auth()->user()->role == 'super_admin' || auth()->user()->role ==='admin')
                                    <th class="text-secondary">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @foreach ($detailsTransactions as $detailsTransaction)
                                <tr>
                                    <td>{{$detailsTransaction->product->id }}</td>
                                    <td>{{$detailsTransaction->product->name }}</td>
                                    <td>Rp{{number_format($detailsTransaction->price,0, ".",".")}}</td>
                                    <td>{{$detailsTransaction->qty }}</td>
                                    @if (auth()->user()->role == 'super_admin' || auth()->user()->role ==='admin')
                                    <td>Rp{{number_format($detailsTransaction->hpp,0, ".",".")}}</td>
                                    <td>{{$detailsTransaction->is_cashback == 1 ? 'Iya' : 'Tidak'}}</td>
                                    <td>Rp{{number_format($detailsTransaction->cashback_value,0, ".",".")}}</td>
                                    <td>{{$detailsTransaction->qty_cashback_item}}</td>
                                    @endif
                                    <td>Rp{{number_format(($detailsTransaction->price *
                                        $detailsTransaction->qty),0,
                                        ".",".")}}
                                    </td>
                                    <td class="text-capitalize">{{$detailsTransaction->process_status}}</td>
                                    <td class="">{{$detailsTransaction->invoice ?
                                        '#'.$detailsTransaction->invoice->invoice_code :
                                        '-'}}</td>
                                    @if (auth()->user()->role == 'super_admin' || auth()->user()->role ==='admin')
                                    <td class="">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-light  dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Ubah Status
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end px-2">
                                                @if($detailsTransaction->process_status != 'unprocessed')
                                                <li>
                                                    <form
                                                        action="{{route('data.transaksi.detail.changeStatus.update', ['id' => $detailsTransaction->id, 'status' => 'unprocessed'])}}"
                                                        method="POST" class="mb-0">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="text-decoration-none btn p-0 w-100">
                                                            <div class="dropdown-item text-dropdown rounded-2"
                                                                type="button">
                                                                Unprocessed
                                                            </div>
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($detailsTransaction->process_status != 'processing')
                                                <li>
                                                    <form
                                                        action="{{route('data.transaksi.detail.changeStatus.update', ['id' => $detailsTransaction->id, 'status' => 'processing'])}}"
                                                        method="POST" class="mb-0">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="text-decoration-none btn p-0 w-100">
                                                            <div class="dropdown-item text-dropdown rounded-2"
                                                                type="button">
                                                                processing
                                                            </div>
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($detailsTransaction->process_status != 'processed')
                                                <li>
                                                    <form
                                                        action="{{route('data.transaksi.detail.changeStatus.update', ['id' => $detailsTransaction->id,'status' => 'processed'])}}"
                                                        method="POST" class="mb-0">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="text-decoration-none btn p-0 w-100">
                                                            <div class="dropdown-item text-dropdown rounded-2"
                                                                type="button">
                                                                Processed
                                                            </div>
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($detailsTransaction->process_status != 'taken')
                                                <li>
                                                    <form
                                                        action="{{route('data.transaksi.detail.changeStatus.update', ['id' => $detailsTransaction->id,'status' => 'taken'])}}"
                                                        method="POST" class="mb-0">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="text-decoration-none btn p-0 w-100">
                                                            <div class="dropdown-item text-dropdown rounded-2"
                                                                type="button">
                                                                Taken
                                                            </div>
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($detailsTransaction->process_status != 'cancel')
                                                <li>
                                                    <form
                                                        action="{{route('data.transaksi.detail.changeStatus.update', ['id' => $detailsTransaction->id,'status' => 'cancel'])}}"
                                                        method="POST" class="mb-0">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="text-decoration-none btn p-0 w-100">
                                                            <div class="dropdown-item text-dropdown rounded-2"
                                                                type="button">
                                                                Cancel
                                                            </div>
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{$detailsTransactions->links()}}
                </div>
            </div>
        </div>
    </div>
</div>