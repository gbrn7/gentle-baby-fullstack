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
                <div class="info-wrapper row">
                    <div class="col-12 col-sm-4  col-md-3">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Nama Perusahaan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">CV Berkah Jaya</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Pendapatan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp.20.000.000</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Nilai DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp.2.000.000</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Cashback Item</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">50</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4  col-md-3">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Tanggal Transaksi</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">20-12-2022</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Profit</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp.3.000.000</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status Pembayaran</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Sudah Dibayar</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Jatuh Tempo DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">21-12-2022</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4  col-md-3">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status Proses</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Unprocessed</p>
                            </div>
                        </div>

                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Nilai Cashback</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp.200.000</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Belum dibayar</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Jatuh Tempo</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">25-12-2022</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-3">
                        <div class="card text-bg-light mb-3 receipt-card">
                            <div class="card-header text-secondary">Bukti Transfer</div>
                            <form action="#">
                                <div class="card-body receipt-wrapper ratio ratio-1x1">
                                    <label class=" drop-area" id="drop-area">
                                        <input type="file" name="thumbnail" hidden accept="image/*" id="input-file">
                                        <div
                                            class="img-view h-100 w-100 d-flex justify-content-center align-items-center">
                                            <div class="default-view">
                                                <i class='bx bxs-cloud-upload upload-icon custom-upload-icon'></i>
                                                <p class="file-desc file-desc-custom">Drag and drop or click here <br>to
                                                    upload image</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div
                                    class="card-footer d-none text-center d-flex flex-wrap gap-2 justify-content-center">
                                    <div class="btn btn-secondary btn-clear">Hapus Gambar</div>
                                    <button type="submit" class="btn btn-primary btn-upload">Upload Gambar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @break

                @case('admin')
                <div class="info-wrapper row">
                    <div class="col-12 col-sm-4  col-md-3">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Nama Perusahaan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">CV Berkah Jaya</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Pendapatan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp.20.000.000</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status Pembayaran</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Sudah Dibayar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4  col-md-3">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Tanggal Transaksi</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">20-12-2022</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Nilai DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp.2.000.000</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Jatuh Tempo DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">21-12-2022</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4  col-md-3">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status Proses</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Unprocessed</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Belum dibayar</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Jatuh Tempo</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">25-12-2022</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-3">
                        <div class="card text-bg-light mb-3 receipt-card">
                            <div class="card-header text-secondary">Bukti Transfer</div>
                            <form action="#">
                                <div class="card-body receipt-wrapper ratio ratio-1x1">
                                    <label class=" drop-area" id="drop-area">
                                        <input type="file" name="thumbnail" hidden accept="image/*" id="input-file">
                                        <div
                                            class="img-view h-100 w-100 d-flex justify-content-center align-items-center">
                                            <div class="default-view">
                                                <i class='bx bxs-cloud-upload upload-icon custom-upload-icon'></i>
                                                <p class="file-desc file-desc-custom">Drag and drop or click here <br>to
                                                    upload image</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div
                                    class="card-footer d-none text-center d-flex flex-wrap gap-2 justify-content-center">
                                    <div class="btn btn-secondary btn-clear">Hapus Gambar</div>
                                    <button type="submit" class="btn btn-primary btn-upload">Upload Gambar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @break

                @case('super_admin_cust' || 'admin_cust' )
                <div class="info-wrapper row">
                    <div class="col-12 col-sm-4  col-md-3">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Nama Perusahaan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">CV Berkah Jaya</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Tagihan</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp.20.000.000</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Sudah Dibayar</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4  col-md-3">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Tanggal Transaksi</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">20-12-2022</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Tagihan DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Rp.20.000.000</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Jatuh Tempo</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">25-12-2022</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4  col-md-3">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status Proses</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Unprocessed</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Status Pembayaran</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">Belum dibayar</p>
                            </div>
                        </div>
                        <div class="card text-bg-light mb-3">
                            <div class="card-header text-secondary">Jatuh Tempo DP</div>
                            <div class="card-body">
                                <p class="card-title fw-bold">21-12-2022</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-3">
                        <div class="card text-bg-light mb-3 receipt-card">
                            <div class="card-header text-secondary">Bukti Transfer</div>
                            <form action="#">
                                <div class="card-body receipt-wrapper ratio ratio-1x1">
                                    <label class=" drop-area" id="drop-area">
                                        <input type="file" name="thumbnail" hidden accept="image/*" id="input-file">
                                        <div
                                            class="img-view h-100 w-100 d-flex justify-content-center align-items-center">
                                            <div class="default-view">
                                                <i class='bx bxs-cloud-upload upload-icon custom-upload-icon'></i>
                                                <p class="file-desc file-desc-custom">Drag and drop or click here <br>to
                                                    upload image</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div
                                    class="card-footer d-none text-center d-flex flex-wrap gap-2 justify-content-center">
                                    <div class="btn btn-secondary btn-clear">Hapus Gambar</div>
                                    <button type="submit" class="btn btn-primary btn-upload">Upload Gambar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @break
                @endswitch

                <div class="table-wrapper card text-bg-light mb-2 mt-2">
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
                                    <th class="text-secondary sort @if ($sortColumn=='hpp') {{$sortDirection}}@endif"
                                        wire:click="sort('hpp')">hpp</th>
                                    <th class="text-secondary sort @if ($sortColumn=='price') {{$sortDirection}}@endif"
                                        wire:click="sort('price')">harga</th>
                                    <th class="text-secondary sort @if ($sortColumn=='qty') {{$sortDirection}}@endif "
                                        wire:click="sort('qty')">qty</th>
                                    <th class="text-secondary sort @if ($sortColumn=='is_cashback') {{$sortDirection}}@endif"
                                        wire:click="sort('is_cashback')">Status Cashback</th>
                                    <th class="text-secondary sort @if ($sortColumn=='cashback_value') {{$sortDirection}}@endif"
                                        wire:click="sort('cashback_value')">Nilai Cashback</th>
                                    <th class="text-secondary sort @if ($sortColumn=='qty_cashback_item') {{$sortDirection}}@endif"
                                        wire:click="sort('qty_cashback_item')">Total Cashback</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @foreach ($detailsTransactions as $detailsTransaction)
                                <tr>
                                    <td>{{$detailsTransaction->product->id }}</td>
                                    <td>{{$detailsTransaction->product->name }}</td>
                                    <td>{{$detailsTransaction->hpp}}</td>
                                    <td>{{$detailsTransaction->price }}</td>
                                    <td>{{$detailsTransaction->qty }}</td>
                                    <td>{{$detailsTransaction->is_cashback == 1 ? 'Iya' : 'Tidak'}}</td>
                                    <td>{{$detailsTransaction->cashback_value}}</td>
                                    <td>{{$detailsTransaction->qty_cashback_item}}</td>
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
    <script>
        const dropArea = document.querySelector("#drop-area");
        const inputFile = document.querySelector("#input-file");
        const imageView = document.querySelector(".img-view");
        const btnClear = document.querySelector(".btn-clear");
        const cardFooter = document.querySelector(".card-footer");
      
            inputFile.addEventListener('change', uploadImage);
      
            dropArea.addEventListener('dragover', function (e) {
              e.preventDefault();
              dropArea.classList.add('active');
              document.querySelector(".file-desc").textContent = "Release to upload file";
              imageView.classList.remove("border-0");
            })
      
            dropArea.addEventListener("dragleave", ()=>{
            document.querySelector(".file-desc").innerHTML = "Drag and drop or click here <br>to upload image";
            dropArea.classList.remove('active');
            imageView.classList.add("border-0");
          });
      
            dropArea.addEventListener('drop', function (e) {
              e.preventDefault();
              inputFile.files = e.dataTransfer.files;
              uploadImage();
            })
      
            btnClear.addEventListener('click', function(){
              document.querySelector(".default-view").classList.remove("d-none");
              cardFooter.classList.add("d-none");
              dropArea.classList.remove("active")
              imageView.style.backgroundImage = `none`;
              inputFile.files = null;
              imageView.classList.remove("border-0");
            document.querySelector(".file-desc").innerHTML = "Drag and drop or click here <br>to upload image";
            });
      
            function uploadImage() {
              let imgLink = URL.createObjectURL(inputFile.files[0]);
              imageView.style.backgroundImage = `url(${imgLink})`;
              document.querySelector(".default-view").classList.add("d-none");
              cardFooter.classList.remove("d-none");
              imageView.classList.add("border-0");
            }
    </script>
</div>