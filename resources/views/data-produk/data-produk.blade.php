@extends('layouts.base')

@section('content')
<div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-instance-line fs-2"></i>
  <p class="fs-3 m-0">Data Produk</p>
</div>
<div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
      <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby</li>
      <li class="breadcrumb-item active" aria-current="page">Data Produk</li>
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
      <a href={{route('data.product.create')}}>
        <div id="add" class="btn btn-success"><i class="ri-add-box-line me-2"></i>Tambah Produk</div>
      </a>
    </div>
    <div class="table-wrapper mt-2 mb-2">
      <table id="example" class="table mt-3 table-hover table-borderless" style="width: 100%">
        <thead>
          <tr>
            <th class="text-secondary">ID</th>
            <th class="text-secondary">Nama</th>
            <th class="text-secondary">HPP</th>
            <th class="text-secondary">Price</th>
            <th class="text-secondary">Ukuran Volume</th>
            <th class="text-secondary">Is Cashback</th>
            <th class="text-secondary">Nilai Cashback</th>
            <th class="text-secondary">Status</th>
            <th class="text-secondary">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @foreach ($products as $product)
          <tr>
            <td>{{$product->id }}</td>
            <td>{{$product->name }}</td>
            <td>Rp{{number_format($product->hpp,0, ".", ".")}}</td>
            <td>Rp{{number_format($product->price,0, ".", ".")}}</td>
            <td>{{$product->size_volume }}</td>
            <td>{{$product->is_cashback == 1 ? 'Ya' : 'Tidak'}}</td>
            <td>Rp{{number_format($product->cashback_value,0, ".", ".")}}</td>
            <td class="text-capitalize">{{$product->status}}</td>
            <td class="">
              <div class="btn-wrapper d-flex gap-2 flex-wrap">
                <a href={{route('data.product.edit', $product->id)}} data-bs-toggle="tooltip"
                  data-bs-custom-class="custom-tooltip" data-bs-title="Edit data produk" class="btn edit btn-action
                  btn-warning
                  text-white"><i class="bx bx-edit"></i></a>
                <a href={{route('data.product.delete', $product->id)}} class="delete btn btn-action btn-danger
                  text-white"
                  data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="Hapus data produk"
                  data-name="{{$product->name}}" data-id="{{$product->id}}">
                  <i class="bx bx-trash"></i>
                </a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Hapus Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4 class="text-center">Apakah anda yakin mengapus Produk <span class="produk-name"></span>?</h4>
      </div>
      <form action={{route('data.product.delete')}} method="post">
        @method('delete')
        @csrf
        <input type="hidden" name="id" id="delete-id">
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="deletecriteria" class="btn btn-danger">Hapus</button>
      </form>
    </div>
  </div>
</div>
</div>

@push('js')
<script type="text/javascript">
  $(document).ready(function(){
      $(document).on('click', '.delete', function(event){
          event.preventDefault();
          var id = $(this).data('id');
          var name = $(this).data('name');
          $('#deletemodal').modal('show');
          $('.produk-name').html(name);
          $('#delete-id').val(id);
      });  

  });    
</script>
@endpush

@endsection