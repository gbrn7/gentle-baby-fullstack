@extends('layouts.base')

@section('content')
<div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-instance-line fs-2"></i>
  <p class="fs-3 m-0">Tambah Data Produk</p>
</div>
<div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
  <nav
    style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
    aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
      <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Baby Gentle</li>
      <li class="breadcrumb-item"><a href={{route('data.product')}} class="text-decoration-none">Data Produk</a>
      <li class="breadcrumb-item active" aria-current="page">Tambah Data Produk</li>
    </ol>
  </nav>
</div>
<div class="content-box mb-md-0 mb-5 p-3 mt-3 rounded rounded-2 bg-white">
  <div class="content rounded rounded-2 border border-1 p-2">
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
    <form action={{route('data.admin.store')}} enctype="multipart/form-data" id="addForm" method="POST">
      @csrf
      <div class="p-0 w-100 mt-2 mb-2">
        <div class="row justify-content-start">
          <div class="col-12 col-md-5 col-sm text-center">
            <label class="w-100 drop-area" id="drop-area">
              <input type="file" hidden accept="image/*" id="input-file">
              <div class="img-view w-100 d-flex justify-content-center align-items-center">
                <div class="default-view">
                  <i class='bx bxs-cloud-upload upload-icon'></i>
                  <p class="file-desc">Drag and drop or click here <br>to upload image</p>
                </div>
              </div>
            </label>
            <div class="btn btn-secondary d-none btn-clear">Hapus Gambar</div>
          </div>
          <div class="col-12 col-md-5 mt-3 mt-md-0">
            <div class="form-group mb-3">
              <label for="name" class="mb-1">Nama</label>
              <input value="{{old('name')}}" required class="form-control" type="text" name="name" id="name"
                placeholder="Masukkan nama produk" />
            </div>
            <div class="form-group mb-3">
              <label for="email" class="mb-1">HPP</label>
              <input value="{{old('hpp')}}" required class="form-control" type="number" name="hpp" id="hpp"
                placeholder="Masukkan hpp produk" />
            </div>
            <div class="form-group mb-3">
              <label for="price" class="mb-1">Harga</label>
              <input required class="form-control" type="number" name="price" id="price"
                placeholder="Masukkan harga produk" />
            </div>
            <div class="form-group mb-3">
              <label for="size_volume" class="mb-1">Ukuran Volume</label>
              <input required class="form-control" type="number" name="size_volume" id="size_volume"
                placeholder="Masukkan Ukuran Volume" />
            </div>
            <div class=" form-group mb-3">
              <label for="is_cashback" class="mb-1">Status Cashback</label>
              <select required id="is_cashback" name="is_cashback" class="form-select status"
                aria-label="Default select example">
                <option class="text-secondary" value="">
                  Klik untuk status cashback
                </option>
                <option {{old('is_cashback' )===1 ? 'selected' : '' }} value="1" class="text-secondary">
                  iya
                </option>
                <option {{old('is_cashback' )===0 ? 'selected' : '' }} value="0" class="text-secondary">Tidak</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <label for="cashback_value" class="mb-1">Nilai Cashback</label>
              <input value="{{old('cashback_value')}}" class="form-control" type="number" name="cashback_value"
                id="cashback_value" placeholder="Masukkan nilai cashback" />
            </div>
            <div class=" form-group mb-3">
              <label for="is_cashback" class="mb-1">Status Produk</label>
              <select required id="status" name="status" class="form-select status" aria-label="Default select example">
                <option class="text-secondary" value="">
                  Klik untuk status produk
                </option>
                <option {{old('status' )===1 ? 'active' : '' }} value="1" class="text-secondary">
                  iya
                </option>
                <option {{old('status' )===0 ? 'inActive' : '' }} value="0" class="text-secondary">Tidak
                </option>
              </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
</div>

@push('js')
<script>
  const dropArea = document.querySelector("#drop-area");
  const inputFile = document.querySelector("#input-file");
  const imageView = document.querySelector(".img-view");
  const btnClear = document.querySelector(".btn-clear");

      inputFile.addEventListener('change', uploadImage);

      dropArea.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropArea.classList.add('active');
        document.querySelector(".file-desc").textContent = "Release to upload file";
        dragText.textContent = "Drag & Drop to Upload File";
      })

      dropArea.addEventListener("dragleave", ()=>{
      document.querySelector(".file-desc").innerHTML = "Drag and drop or click here <br>to upload image";
      dropArea.classList.remove('active');
      dragText.textContent = "Drag & Drop to Upload File";
    });

      dropArea.addEventListener('drop', function (e) {
        e.preventDefault();
        inputFile.files = e.dataTransfer.files;
        uploadImage();
      })

      btnClear.addEventListener('click', function(){
        document.querySelector(".default-view").classList.remove("d-none");
        document.querySelector(".btn-clear").classList.add("d-none");
        dropArea.classList.remove("active")
        imageView.style.backgroundImage = `none`;
        inputFile.files = null;
        imageView.classList.remove("border-0");
      });

      function uploadImage() {
        let imgLink = URL.createObjectURL(inputFile.files[0]);
        imageView.style.backgroundImage = `url(${imgLink})`;
        document.querySelector(".default-view").classList.add("d-none");
        document.querySelector(".btn-clear").classList.remove("d-none");
        imageView.classList.add("border-0");
      }
</script>
@endpush
@endsection