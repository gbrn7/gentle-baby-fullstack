<div>
    <div>
        <div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-survey-line fs-2"></i>
            <p class="fs-3 m-0">Pemesanan Produk</p>
        </div>
        <div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby
                    </li>
                    <li class="breadcrumb-item"><a href={{route('data.transaksi')}}
                            class="text-decoration-none">Pemesanan Produk</a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="content-box mt-3 rounded rounded-2">
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

                <div class="row justify-content-between gap-x-1 pb-5">
                    <div class="col-12 col-md-7 p-0 left-section">
                        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                        <div class="card p-0 col-12 col-lg-11 company-list-wrapper">
                            <div class="card-header text-bold text-center">Daftar Perusahaan</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="keyword" class="mb-1 text-left">Search :</label>
                                    <div class="input-group">
                                        <select wire:model.live="companyColFil" class="form-select">
                                            <option value="c.name">Nama Perusahaan</option>
                                            <option value="u.name">Nama Pemilik</option>
                                            <option value="c.address">Alamat Perusahaan</option>
                                        </select>
                                        <input class="form-control col-8" type="text" wire:model.live="companyFilVal" />
                                    </div>
                                </div>
                                <div class="table-wrapper-custom overflow-auto">
                                    <table class="table mt-3 table-hover table-borderless">
                                        <thead>
                                            <tr>
                                                <th class="text-secondary">Nama Perusahaan</th>
                                                <th class="text-secondary">Nama Pemilik</th>
                                                <th class="text-secondary">Alamat Perusahaan</th>
                                                <th class="text-secondary">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($companies as $company)
                                            <tr>
                                                <td>{{$company->companyName}}</td>
                                                <td>{{$company->ownerName}}</td>
                                                <td>{{$company->companyAddress}}</td>
                                                <td class="">
                                                    <button type="button"
                                                        wire:click="addCompanyCart({{json_encode($company)}})"
                                                        class="btn btn-light">
                                                        Pilih
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td rowspan="3">No matching records found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if(empty($companyFilVal))
                                {{$companies->links()}}
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="card p-0 col-12 col-lg-11 product-wrapper mt-md-3">
                            <div class="card-header text-bold text-center">Daftar Produk</div>
                            <div class="card-body">
                                <div class="form-group ">
                                    <label for="keyword" class="mb-1 text-left">Search :</label>
                                    <div class="input-group">
                                        <select wire:model.live="productColFil" class="form-select">
                                            <option value="name">Nama Produk</option>
                                            <option value="price">Harga Produk</option>
                                            <option value="size_volume">Ukuran Volume</option>
                                        </select>
                                        <input class="form-control col-8" type="text" wire:model.live="productFilVal" />
                                    </div>
                                </div>
                                <div class="product-wrapper row m-0 row-cols-2 row-cols-lg-4 gy-3">
                                    @forelse ($products as $product)
                                    <div class="product p-2">
                                        <div class="box d-flex flex-column">
                                            <div class="product-img"><img loading="lazy"
                                                    src="{{ asset('storage/produk/'.($product->thumbnail? $product->thumbnail : 'defaultProduct.jpg'))}}"
                                                    class="img-fluid">
                                            </div>
                                            <div class="product-desc p-2">
                                                <div class="title">{{$product->name}}</div>
                                                <div class="footer d-flex justify-content-between pt-2">
                                                    <div class="price">Rp{{number_format($product->price,0, ".", ".")}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-cart-wrapper px-2 pt-1 pb-3"
                                                wire:click='addProductCart({{$product}})'>
                                                <button class="btn-cart text-center">
                                                    <p class="btn-text m-0">Masukkan Keranjang</p>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <p>No matching records found</p>
                                    @endforelse
                                </div>
                                <div class="pagination-wrapper p-1">
                                    <ul
                                        class="pagination d-flex flex-wrap justify-content-start pagination-xsm m-0 mt-2">
                                        @for ($i = 0; $i < $productPages; $i++) <li
                                            class="page-item {{$i + 1 == $page && 'disabled'}}">
                                            <div class="page-link cursor-pointer paginate-item"
                                                wire:click="productPagination({{$i+1}})" aria-label="Page {{$i + 1}}">
                                                <span>{{$i + 1}}</span>
                                            </div>
                                            </li>
                                            @endfor
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" card p-0 col-12 mt-3 mt-md-0 col-md-5 cart-wrapper">
                        <div class="card-header text-bold text-center">Keranjang</div>
                        <div class="card-body">

                            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                            <div class="company-cart-section overflow-auto">
                                <label class="mb-1">Perusahaan</label>
                                <table class="table table-hover company-cart-table table-borderless">
                                    <thead>
                                        <th class="text-secondary">Nama Perusahaan</th>
                                        <th class="text-secondary">Nama Pemilik</th>
                                        <th class="text-secondary">Alamat Perusahaan</th>
                                        <th class="text-secondary">Aksi</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @if(!empty($companyCart))
                                            <td>{{$companyCart['companyName']}}</td>
                                            <td>{{$companyCart['ownerName']}}</td>
                                            <td>{{$companyCart['companyAddress']}}</td>
                                            <td class="">
                                                <button type="button" class="btn btn-light">
                                                    <i class='bx bx-trash' wire:click='removeCompanyCart'></i> </button>
                                            </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            <div class="product-cart-section mt-2">
                                <label class="mb-1">Produk</label>
                                <div class="product-cart-wrapper d-flex flex-column gap-2">
                                    @php($total = 0)
                                    @php($totalQty = 0)
                                    @foreach ($productsCart as $product)
                                    @php($total += ($product['price'] * $product['qty']))
                                    @php($totalQty += $product['qty'])
                                    <div class="cart-item-wrapper p-0 m-0 row align-items-center">
                                        <div class="img-wrapper p-0 col-2">
                                            <img loading="lazy"
                                                src="{{ asset('storage/produk/'.(empty($product['thumbnail'])  ? 'defaultProduct.jpg' : $product['thumbnail']))}}"
                                                class="img-fluid">
                                        </div>
                                        <div class="col-3 title">{{$product['name']}}</div>
                                        <div
                                            class="col-3 qty-btn d-flex p-0 justify-content-center gap-2 align-items-center">
                                            <div class="minus" class="col-3"
                                                wire:click='decrementProductCart({{$loop->index}})'>-</div>
                                            <input name="qty" type="number" min="0" step="1"
                                                wire:model.live.number="productsCart.{{$loop->index}}.qty"
                                                class="col-6 qty-input">
                                            <div class="plus" class="col-3"
                                                wire:click='incrementProductCart({{$loop->index}})'>+</div>
                                        </div>
                                        <div class="col-3 p-0 price-wrapper">
                                            <p class="m-0">Rp{{number_format(($product['price'] * $product['qty']) ,0,
                                                ".", ".")}}</p>
                                        </div>
                                        <div class="col-1 p-0 delete-wrap text-center"
                                            wire:click="removeProductCart({{$loop->index}})"><i class='bx bx-trash'></i>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="total-amount-section mt-4 bg-primary p-4 rounded-3">
                                <div class="total-qty-wrapper d-flex justify-content-between">
                                    <p class="mb-0 text-white">Total Quantity</p>
                                    <p class="mb-0 text-white">{{$totalQty}}</p>
                                </div>
                                <div class="total-wrapper d-flex justify-content-between mt-2">
                                    <p class="mb-0 text-white total-label">Total</p>
                                    <p class="mb-0 text-white total-label">Rp{{number_format($total,0,".",".")}}</p>
                                </div>
                            </div>

                            <div class="checkout-btn btn btn-success w-100 rounded-3 text-center text-white mt-2 p-3"
                                wire:click='createTransaction()'>
                                Checkout
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @script
    <script>
        const checkouBtn = document.querySelector(".checkout-btn");
        checkouBtn.addEventListener('click', function (){
            document.querySelector("html").style.cursor = "wait";
            document.querySelector(".loading-wrapper").classList.remove('d-none');
        })
    </script>
    @endscript
</div>