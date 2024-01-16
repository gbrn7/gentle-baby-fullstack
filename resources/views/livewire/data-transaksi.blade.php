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

            <div class="table-wrapper mb-2 pb-5 ">
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
                                wire:click="sort('transaction_complete_date')">Tanggal Selesai
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
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end px-2">
                                        <li>
                                            <a class="text-decoration-none" href={{route('data.transaksi.detail',
                                                $transaction->id)}}>
                                                <div class="dropdown-item rounded-2" type="button">
                                                    Detail transaksi
                                                </div>
                                            </a>
                                        </li>
                                        <li class="cursor-pointer">
                                            <a href="#" class="text-decoration-none">
                                                <div class="dropdown-item rounded-2 edit cursor-pointer"
                                                    data-edit-id="{{$transaction->id}}"
                                                    data-transaction-code="{{$transaction->transaction_code}}"
                                                    data-company-name="{{$transaction->company->name}}"
                                                    data-payment-status="{{$transaction->payment_status}}"
                                                    data-process-status="{{$transaction->process_status}}"
                                                    data-dp-status="{{$transaction->dp_status}}">
                                                    Edit status
                                                </div>
                                        </li>
                                        </a>
                                        <li>
                                            <div class="dropdown-item rounded-2 button-pdf"
                                                wire:click="downloadPDF({{$transaction->id}})" type="button">
                                                Download invoice
                                            </div>
                                        </li>
                                        <li>
                                            <a class="text-decoration-none button-pdf" target="_blank"
                                                href={{route('data.transaksi.viewPDF', $transaction->id)}}>
                                                <div class="dropdown-item rounded-2" type="button">
                                                    View Invoice
                                                </div>

                                            </a>
                                        </li>
                                    </ul>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <form action={{route("data.transaksi.update.status")}} id="editForm" method="POST">
            @method('put')
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Edit Status Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="transaction_id" id="edit-id">
                        <div class="form-group mb-3">
                            <label for="nama" class="mb-1">Kode Transaksi</label>
                            <input class="form-control" type="text" name="transaction_code" id="transaction-code"
                                disabled />
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama" class="mb-1">Nama Perusahaan</label>
                            <input class="form-control" type="text" name="companyName" id="companyName" disabled />
                        </div>
                        @if(auth()->user()->role == 'super_admin')
                        <div class="form-group mb-3">
                            <label for="Status" class="mb-1">Status Pembayaran</label>
                            <select required id="payment-status" name="payment_status" class="form-select status"
                                aria-label="Default select example">
                                <option value="0" class="text-secondary">Belum Dibayar</option>
                                <option value="1" class="text-secondary">Terbayar</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="Status" class="mb-1">Status DP</label>
                            <select required id="dp-status" name="dp_status" class="form-select status"
                                aria-label="Default select example">
                                <option value="0" class="text-secondary">Belum Dibayar</option>
                                <option value="1" class="text-secondary">Terbayar</option>
                            </select>
                        </div>
                        @endif
                        <div class="form-group mb-3">
                            <label for="Status" class="mb-1">Status Proses</label>
                            <select required id="process_status" name="process_status" class="form-select status"
                                aria-label="Default select example">
                                <option value="unprocessed" class="text-secondary text-capitalize">unprocessed</option>
                                <option value="processing" class="text-secondary text-capitalize">processing</option>
                                <option value="processed" class="text-secondary text-capitalize">processed</option>
                                <option value="taken" class="text-secondary text-capitalize">taken</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-white">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @script
    <script type="text/javascript">
        $(document).on('click', '.edit', function (event){
                event.preventDefault();
                const editId = $(this).data('edit-id');
                const transactionCode = $(this).data('transaction-code');
                const companyName = $(this).data('company-name');
                const paymentStatus = $(this).data('payment-status');
                const processStatus = $(this).data('process-status');
                const dpStatus = $(this).data('dp-status');
                
                $('#editmodal').modal('show');
                $('#edit-id').val(editId);
                $('#transaction-code').val(transactionCode);
                $('#companyName').val(companyName);
                $('#payment-status').val(paymentStatus);
                $('#process_status').val(processStatus);
                $('#dp-status').val(dpStatus);
            });
        
        $(document).on('submit', '#editForm', function (event){
            event.preventDefault();
            document.querySelector("html").style.cursor = "wait";
            this.submit();        
        });
    

    </script>
    @endscript
</div>