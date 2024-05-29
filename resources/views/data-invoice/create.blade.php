@extends('layouts.base')

@section('content')
<div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-article-fill fs-2"></i>
  <p class="fs-3 m-0">Tambah Invoice </p>
</div>
<div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
      <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby</li>
      <li class="breadcrumb-item" aria-current="page"><a href="{{route('data-invoice.index')}}"
          class="text-decoration-none">Data Invoice</a></li>
      <li class="breadcrumb-item d-flex gap-2 align-items-center active"><i class="ri-apps-line"></i>Tambah Invoice
      </li>
    </ol>
  </nav>
</div>
<div class="content-box mt-3 rounded rounded-2 bg-white">
  <div class="content rounded rounded-2 border border-1 p-3">
    <form action="{{route('data-invoice.create')}}" method="get">
      <div class="filter-wrapper d-lg-flex align-items-end gap-2 mt-2">
        <div class="form-group col-12 col-lg-4">
          <label>Nama Perusahaan</label>
          <select name="company_id" class="form-select">
            <option value="">Pilih Perusahaan</option>
            @foreach ($companies as $company)
            <option value="{{$company->company_id}}" @selected(request()->get('company_id') ==
              $company->company_id)>{{$company->company_name}}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-12 mt-2 mt-lg-0 col-lg-2">
          <button class="btn w-100 btn-success" type="submit">Apply</button>
        </div>
      </div>
    </form>
    <div class="table-wrapper mt-2 mb-2">
      <form action="{{request()->get('company_id') ? route('data-invoice.store', [" company_id"=>
        request()->get('company_id')]) : '#'}}"
        method="POST">
        @csrf
        @if (isset($transactionDetails) > 0)
        <div class="btn-wrapper d-flex gap-2 justify-content-end align-items-end">
          <div class="total-wrapper">
            <label for="">Total Transaksi</label>
            <div class="mb-0 fs-2 bg-primary text-white  px-2 rounded rounded-3 mt-1">Rp.<span class="total">0</span>
            </div>
          </div>
          <button type="submit" disabled class="btn btn-success btn-generate-invoice"><i
              class="ri-add-box-line me-2"></i>Buat
            Invoice</button>
        </div>
        @endif
        <table class="table-jquery table mt-3 table-hover table-borderless" style="width: 100%">
          <thead>
            <tr>
              <th><input type="checkbox" class="checkbox-parent"></th>
              <th>Kode Transaksi</th>
              <th>Nama Produk</th>
              <th>Qty</th>
              <th>Harga Jual</th>
              <th>Sub Total</th>
              <th>Tanggal Transaksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($transactionDetails as $item)
            <tr>
              <td><input type="checkbox" value="{{$item->td_id}}" class="checkbox-item" name="transaction_detail_id[]"
                  subTotal="{{$item->td_price * $item->td_qty}}"></td>
              <td>#{{$item->t_code}}</td>
              <td>{{$item->p_name}}</td>
              <td>{{$item->td_qty}}</td>
              <td>Rp{{number_format($item->td_price,0, ".", ".")}}</td>
              <td>Rp.{{number_format(($item->td_price * $item->td_qty),0, ".", ".")}}</td>
              <td>{{date("d-m-Y", strtotime($item->t_created_at)) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
  integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="{{asset('Assets/Js/jquery.simple-checkbox-table.min.js')}}"></script>
<script>
  let total = 0;
  let mount = true;
  $(document).ready(function(){
    
    $("table").simpleCheckboxTable({

    onCheckedStateChanged: function(checkbox) {

      if(checkbox.is(':checked') == true){
        total +=  Number(checkbox.attr('subTotal'));
      }else{
        if(total > 0){
          total -=  Number(checkbox.attr('subTotal'));
        }
      }

      if(total > 0){
        $('.total').html(total.toLocaleString('de-DE'));
        $('.btn-generate-invoice').attr('disabled', false);
      }else{
        $('.total').html('0');
        $('.btn-generate-invoice').attr('disabled', true);
      }
    }

  });



});
</script>
@endpush