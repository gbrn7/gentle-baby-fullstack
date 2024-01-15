<div>
    <div>
        <div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-arrow-left-right-line fs-2"></i>
            <p class="fs-3 m-0">Data Detail Transaksi {{$transaction->transaction_code}}</p>
        </div>
        <div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Baby
                        Gentle
                    </li>
                    <li class="breadcrumb-item"><a href={{route('data.transaksi')}} class="text-decoration-none">Data
                            Transaksi</a>
                    <li class="breadcrumb-item active" aria-current="page">Detail Transaksi
                        {{$transaction->transaction_code}}
                    </li>
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
                @switch(auth()->user()->role)

                @case('super_admin')
                <form method="POST" action={{route('data.transaksi.detail.update', $transaction->id)}}
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="info-wrapper row">
                        <div class="col-12 col-sm-4">
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Nama Perusahaan</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->companyName}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Nilai Pelunasan</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">Rp {{number_format($transaction->revenue,0, ".",
                                        ".")}}
                                    </p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Nilai DP</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">Rp {{number_format($transaction->dp_value,0, ".",
                                        ".")}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Cashback Item</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->cashback_item}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3 dp-receipt-card">
                                <div class="card-header text-secondary">Bukti Transfer DP</div>
                                <form>
                                    <div class="card-body receipt-wrapper ratio ratio-1x1">
                                        <label class=" drop-area" id="drop-area">
                                            <input type="file" name="dp_payment_receipt" hidden accept="image/*"
                                                id="input-file">
                                            <div
                                                class="img-view h-100 w-100 d-flex justify-content-center align-items-center">
                                                <div class="default-view">
                                                    <i class='bx bxs-cloud-upload  custom-upload-icon'></i>
                                                    <p class="file-desc file-desc-custom">Drag and drop or click here
                                                        <br>to
                                                        upload image
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Tanggal Transaksi</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->transactionDate}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Profit</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">Rp {{number_format($transaction->profit,0, ".",
                                        ".")}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Status Pelunasan</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->payment_status === 1 ? 'Terbayar' :
                                        'Belum
                                        Dibayar'}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Jatuh Tempo DP</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->jatuh_tempo_dp ?
                                        $transaction->jatuh_tempo_dp : "-"}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3 full-receipt-card">
                                <div class="card-header text-secondary">Bukti Transfer Pelunasan</div>
                                <div class="card-body receipt-wrapper ratio ratio-1x1">
                                    <label class=" drop-area" id="drop-area">
                                        <input type="file" name="full_payment_receipt" hidden accept="image/*"
                                            id="input-file">
                                        <div
                                            class="img-view h-100 w-100 d-flex justify-content-center align-items-center">
                                            <div class="default-view">
                                                <i class='bx bxs-cloud-upload  custom-upload-icon'></i>
                                                <p class="file-desc file-desc-custom">Drag and drop or click here <br>to
                                                    upload image</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Status Proses</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->processStatus}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Nilai Cashback</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">Rp.{{$transaction->cashback}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Status DP</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->dp_status === 1 ? 'Terbayar' : 'Belum
                                        Dibayar'}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Jatuh Tempo</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->jatuh_tempo}}</p>
                                </div>
                            </div>
                            <div class="card action-card text-bg-light mb-3">
                                <div class="card-header text-secondary">Aksi</div>
                                <div class="card-body">
                                    <button type="submit" class="btn btn-warning text-white">Perbarui Data</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @break

                @case('admin')
                <div class="info-wrapper row">
                    <div class="col-12 col-sm-4">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Nama Perusahaan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->companyName}}</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Nilai Pelunasan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp {{number_format($transaction->revenue,0, ".", ".")}}
                                </p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status Pelunasan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->payment_status === 1 ? 'Terbayar' :
                                    'Belum
                                    Dibayar'}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Tanggal Transaksi</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->transactionDate}}</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Nilai DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp {{number_format($transaction->dp_value,0, ".",
                                    ".")}}</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Jatuh Tempo DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->jatuh_tempo_dp ?
                                    $transaction->jatuh_tempo_dp : "-"}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status Proses</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->processStatus}}</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->dp_status === 1 ? 'Terbayar' : 'Belum
                                    Dibayar'}}</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Jatuh Tempo</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">{{$transaction->jatuh_tempo}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @break

                @case('super_admin_cust' || 'admin_cust' )
                <form method="POST" action={{route('data.transaksi.detail.update', $transaction->id)}}
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="info-wrapper row">
                        <div class="col-12 col-sm-4">
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Nama Perusahaan</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->companyName}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Tagihan DP</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">Rp {{number_format($transaction->dp_value,0, ".",
                                        ".")}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Status DP</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->dp_status === 1 ? 'Terbayar' :
                                        'Belum
                                        Dibayar'}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3 dp-receipt-card">
                                <div class="card-header text-secondary">Bukti Transfer DP</div>
                                <form>
                                    <div class="card-body receipt-wrapper ratio ratio-1x1">
                                        <label class=" drop-area" id="drop-area">
                                            <input type="file" name="dp_payment_receipt" hidden accept="image/*"
                                                id="input-file">
                                            <div
                                                class="img-view h-100 w-100 d-flex justify-content-center align-items-center">
                                                <div class="default-view">
                                                    <i class='bx bxs-cloud-upload  custom-upload-icon'></i>
                                                    <p class="file-desc file-desc-custom">Drag and drop or click here
                                                        <br>to
                                                        upload image
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Tanggal Transaksi</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->transactionDate}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Tagihan Pelunasan</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">Rp {{number_format($transaction->revenue,0, ".",
                                        ".")}}
                                    </p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Jatuh Tempo Dp</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->jatuh_tempo_dp ?
                                        $transaction->jatuh_tempo_dp : "-"}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3 full-receipt-card">
                                <div class="card-header text-secondary">Bukti Transfer Pelunasan</div>
                                <div class="card-body receipt-wrapper ratio ratio-1x1">
                                    <label class=" drop-area" id="drop-area">
                                        <input type="file" name="full_payment_receipt" hidden accept="image/*"
                                            id="input-file">
                                        <div
                                            class="img-view h-100 w-100 d-flex justify-content-center align-items-center">
                                            <div class="default-view">
                                                <i class='bx bxs-cloud-upload  custom-upload-icon'></i>
                                                <p class="file-desc file-desc-custom">Drag and drop or click here <br>to
                                                    upload image</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Status Proses</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->processStatus}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Status Pembayaran</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->payment_status === 1 ? 'Terbayar' :
                                        'Belum
                                        Dibayar'}}</p>
                                </div>
                            </div>
                            <div class="card text-bg-light mb-3">
                                <div class="card-header text-secondary">Jatuh Tempo</div>
                                <div class="card-body">
                                    <p class="card-title fw-bold">{{$transaction->jatuh_tempo}}</p>
                                </div>
                            </div>
                            <div class="card action-card text-bg-light mb-3">
                                <div class="card-header text-secondary">Aksi</div>
                                <div class="card-body">
                                    <button type="submit" class="btn btn-warning text-white">Perbarui Data</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @break
                @endswitch

                <div class="table-wrapper card text-bg-light mb-5 mt-2">
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
                                    @if (auth()->user()->role == 'super_admin' || auth()->user()->role === 'admin')
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
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @foreach ($detailsTransactions as $detailsTransaction)
                                <tr>
                                    <td>{{$detailsTransaction->product->id }}</td>
                                    <td>{{$detailsTransaction->product->name }}</td>
                                    <td>Rp {{number_format($detailsTransaction->price,0, ".",".")}}</td>
                                    <td>{{$detailsTransaction->qty }}</td>
                                    @if (auth()->user()->role == 'super_admin' || auth()->user()->role === 'admin')
                                    <td>{{$detailsTransaction->hpp}}</td>
                                    <td>{{$detailsTransaction->is_cashback == 1 ? 'Iya' : 'Tidak'}}</td>
                                    <td>{{$detailsTransaction->cashback_value}}</td>
                                    <td>{{$detailsTransaction->qty_cashback_item}}</td>
                                    @endif
                                    <td>Rp {{number_format(($detailsTransaction->price * $detailsTransaction->qty),0,
                                        ".",".")}}</td>
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

    @script
    <script>
        const dropAreas = document.querySelectorAll("#drop-area");
        var toastMixin = Swal.mixin({
        toast: true,
        icon: 'success',
        title: 'General Title',
        animation: false,
        position: 'top-right',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    const Toast = Swal.mixin({
  toast: true,
  position: 'top-right',
  iconColor: 'white',
  customClass: {
    popup: 'colored-toast',
  },
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
})


        @if (session()->has('success'))
        console.log('first')
        Toast.fire({
        icon: 'success',
        title: "{{session('success')}}",
        })  
        @endif

        @if ($transaction->dp_payment_receipt)
        console.log('check');
        setDpPaymentImage();
        @endif

        @if ($transaction->full_payment_receipt)
        console.log('check');
        setFullPaymentImage();
        @endif

        function setDpPaymentImage(){
        const dpReceipCart = document.querySelector(".dp-receipt-card");
        const imageView = dpReceipCart.querySelector(".img-view")
        const defaultView = dpReceipCart.querySelector(".default-view");
        const inputFile = dpReceipCart.querySelector("#input-file");

        imageView.style.backgroundImage = "url({{asset('Storage/paymentReceipt/'.$transaction->dp_payment_receipt)}})";
        defaultView.classList.add("d-none");
        imageView.classList.add("border-0");
      }

        function setFullPaymentImage(){
        const fullReceipCart = document.querySelector(".full-receipt-card");
        const imageView = fullReceipCart.querySelector(".img-view")
        const defaultView = fullReceipCart.querySelector(".default-view")
        const inputFile = fullReceipCart.querySelector("#input-file");

        imageView.style.backgroundImage = "url({{asset('Storage/paymentReceipt/'.$transaction->full_payment_receipt)}})";
        defaultView.classList.add("d-none");
        imageView.classList.add("border-0");
      }

            
            dropAreas.forEach((dropArea) => {
                const imageView =  dropArea.querySelector(".img-view");
                const fileDesc =  dropArea.querySelector(".file-desc");
                const inputFile =  dropArea.querySelector("#input-file");
                const form = dropArea.parentNode.parentNode;
                const cardFooter = form.querySelector(".card-footer");
                const btnClear =  form.querySelector(".btn-clear");
                const defaultView =  form.querySelector(".default-view");

                inputFile.addEventListener('change', () => {
                    let imgLink = window.URL.createObjectURL(inputFile.files[0]);
                    imageView.style.backgroundImage = `url(${imgLink})`;
                    defaultView.classList.add("d-none");
                    cardFooter.classList.remove("d-none");                    
                    imageView.classList.add("border-0");
                });

                dropArea.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    dropArea.classList.add('active');
                    fileDesc.textContent = "Release to upload file";
                    imageView.classList.remove("border-0");
                })
        
                dropArea.addEventListener("dragleave", ()=>{
                    fileDesc.innerHTML = "Drag and drop or click here <br>to upload image";
                    dropArea.classList.remove('active');
                    imageView.classList.add("border-0");
                });
        
                dropArea.addEventListener('drop', function (e) {
                    e.preventDefault();
                    inputFile.files = e.dataTransfer.files;
                    
                    //upload image
                    let imgLink = window.URL.createObjectURL(inputFile.files[0]);
                    imageView.style.backgroundImage = `url(${imgLink})`;
                    defaultView.classList.add("d-none");
                    imageView.classList.add("border-0");
                })


            })

      
      
    </script>
    @endscript
</div>