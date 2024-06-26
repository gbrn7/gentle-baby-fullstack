<div>
    <div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-arrow-left-right-line fs-2"></i>
        <p class="fs-3 m-0">Data Transaksi</p>
    </div>


    <div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby</li>
                <li class="breadcrumb-item active" aria-current="page">Data Transaksi
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

            <div class="filter-wrapper row">
                <div class="form-group col-12 mt-2 mt-xl-0 mt-2 mt-xl-0 col-xl-1">
                    <label class="mb-1 text-left">Show :</label>
                    <select wire:model.live.debounce.300ms="pagination" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="form-group col-12 mt-2 mt-xl-0 mt-2 mt-xl-0 col-xl-3">
                    <label for="keyword" class="mb-1 text-left">Search :</label>
                    <div class="input-group">
                        <select wire:model.live.debounce.300ms="columnFilter" class="form-select">
                            <option value="t.transaction_code">Kode Transaksi</option>
                            <option value="t.id">ID</option>
                            @if (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                            <option value="c.name">Nama Perusahaan</option>
                            @endif
                        </select>
                        <input class="form-control" type="text" wire:model.live.debounce.500ms="keywords" />
                    </div>
                </div>
                <div class="form-group col-12 mt-2 mt-xl-0 col-xl-2">
                    <label class="mb-1 text-left">Tanggal Transaksi :</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="daterange" name="dates"
                            value="01/01/2018 - 01/15/2018" wire:change='dateOnChange' />
                    </div>
                </div>
            </div>

            <div class="table-wrapper mb-2 pb-5 ">
                <table id="" class="table table-sortable mt-3 table-hover table-borderless" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="text-secondary sort @if ($sortColumn=='t.id') {{$sortDirection}}@endif"
                                wire:click="sort('id')">ID
                            </th>
                            <th class="text-secondary">Kode Transaksi</th>
                            <th class="text-secondary sort @if ($sortColumn=='t.created_at') {{$sortDirection}}@endif"
                                wire:click="sort('created_at')">Tanggal Transaksi</th>
                            @if (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                            <th class="text-secondary">Nama Perusahaan</th>
                            @endif
                            <th class="text-secondary">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{$transaction->id }}</td>
                            <td>#{{$transaction->transaction_code }}</td>
                            <td>{{date("d-m-Y", strtotime($transaction->created_at)) }}</td>
                            @if (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                            <td>{{$transaction->name }}</td>
                            @endif
                            <td>
                                <a class="text-decoration-none btn btn-secondary" data-bs-toggle="tooltip"
                                    data-bs-custom-class="custom-tooltip" data-bs-title="Transaksi Detail"
                                    href={{route('data.transaksi.detail',$transaction->id)}}>
                                    <i class="ri-list-check"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td rowspan="10">No matching records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{$transactions->links()}}
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    @if (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
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
                            <label for="Status" class="mb-1">Status Pelunasan</label>
                            <select required id="payment-status" name="payment_status" class="form-select status"
                                aria-label="Default select example">
                                <option value="0" class="text-secondary">Belum Dibayar</option>
                                <option value="1" class="text-secondary">Sudah Bayar</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="Status" class="mb-1">Status DP</label>
                            <select required id="dp-status" name="dp_status" class="form-select status"
                                aria-label="Default select example">
                                <option value="0" class="text-secondary">Belum Dibayar</option>
                                <option value="1" class="text-secondary">Sudah Bayar</option>
                            </select>
                        </div>
                        @endif
                        <div class="form-group mb-3">
                            <label for="Status" class="mb-1">Status Proses</label>
                            <select required id="process_status" name="process_status" class="form-select status"
                                aria-label="Default select example">
                                <option value="cancel" class="text-secondary text-capitalize">Cancel</option>
                                <option value="unprocessed" class="text-secondary text-capitalize">Unprocessed</option>
                                <option value="processing" class="text-secondary text-capitalize">Processing</option>
                                <option value="processed" class="text-secondary text-capitalize">Processed</option>
                                <option value="taken" class="text-secondary text-capitalize">Taken</option>
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
    @endif


    @script
    <script type="text/javascript">
        $(document).on('click', '.edit', function (event){
                event.preventDefault();
                const editId = $(this).data('edit-id');
                const transactionCode = $(this).data('transaction-code');
                const companyName = $(this).data('company-name');
                
                $('#editmodal').modal('show');
                $('#edit-id').val(editId);
                $('#transaction-code').val(transactionCode);
                $('#companyName').val(companyName);
            });
        // 
        $(document).on('submit', '#editForm', function (event){
            event.preventDefault();
            startLoading();            
            this.submit();        
        });        

        $(function() {
          $('#daterange').daterangepicker({
            timePicker: true,
            startDate: moment().format('YY-MM-DD'),
            endDate:  moment().format('YY-MM-DD'),
            locale: {
              format: 'YY-MM-DD'
            }
          }, function(start, end, label) {
            $wire.dispatchSelf('dateRange', {data: {
                startDate : start.format('YYYY-MM-DD'),
                endDate : end.format('YYYY-MM-DD'),
            }});
         });
        });
    </script>
    @endscript
</div>