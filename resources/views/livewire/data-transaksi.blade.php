<div>
    <div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-arrow-left-right-line fs-2"></i>
        <p class="fs-3 m-0">Data Transaksi</p>
    </div>
    <div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Baby
                    Gentle
                </li>
                <li class="breadcrumb-item active" aria-current="page">Data Transaksi</li>
            </ol>
        </nav>
    </div>
    <div class="content-box p-3 mt-3 rounded rounded-2 bg-white">
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

            <div class="filter-wrapper">
                <div class="form-group col-12 col-md-3">
                    <label for="name" class="mb-1 text-left">Search :</label>
                    <input class="form-control" type="text" wire:model.live.debounce.500ms="keywords" />
                </div>
            </div>

            <div class="table-wrapper mb-2 overflow-auto">
                <table id="" class="table table-sortable mt-3 table-hover table-borderless" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="text-secondary sort @if ($sortColumn=='id') {{$sortDirection}}@endif"
                                wire:click="sort('id')">ID
                            </th>
                            <th class="text-secondary">Kode Transaksi</th>
                            <th class="text-secondary sort @if ($sortColumn=='created_at') {{$sortDirection}}@endif"
                                wire:click="sort('created_at')">Tanggal Transaksi</th>
                            <th class="text-secondary">Nama Perusahaan</th>
                            <th class="text-secondary sort @if ($sortColumn=='amount') {{$sortDirection}}@endif"
                                wire:click="sort('amount')">Nominal</th>
                            <th class="text-secondary sort @if ($sortColumn=='jatuh_tempo') {{$sortDirection}}@endif "
                                wire:click="sort('jatuh_tempo')">Jatuh Tempo</th>
                            <th class="text-secondary sort @if ($sortColumn=='payment_status') {{$sortDirection}}@endif"
                                wire:click="sort('payment_status')">Status Pembayaran</th>
                            <th class="text-secondary">Status Proses</th>
                            <th class="text-secondary sort @if ($sortColumn=='transaction_complete_date') {{$sortDirection}}@endif"
                                wire:click="sort('transaction_complete_date')">Tanggal
                                Transaksi Selesai
                            </th>
                            <th class="text-secondary">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{$transaction->id }}</td>
                            <td>{{$transaction->transaction_code }}</td>
                            <td>{{$transaction->created_at }}</td>
                            <td>{{$transaction->company->name }}</td>
                            <td>Rp {{number_format($transaction->amount,0, ".", ".")}}</td>
                            <td>{{$transaction->jatuh_tempo }}</td>
                            <td>{{$transaction->payment_status == 1 ? 'Terbayar' : 'Belum Dibayar'}}</td>
                            <td class="text-capitalize">{{$transaction->process_status }}</td>
                            <td>{{$transaction->transaction_complete_date ? $transaction->transaction_complete_date :
                                '-'}}</td>
                            <td class="">
                                <div class="btn-wrapper d-flex gap-2 flex-wrap">
                                    <a type="button" href={{route('data.transaksi.detail', $transaction->id)}}
                                        class="btn btn-secondary" data-bs-toggle="tooltip"
                                        data-bs-custom-class="custom-tooltip" data-bs-title="Detail transaksi">
                                        <i class="ri-list-check"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{$transactions->links()}}
            </div>
        </div>
    </div>
</div>