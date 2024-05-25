@extends('layouts.base')

@section('content')
<div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-article-fill fs-2"></i>
  <p class="fs-3 m-0">Data Invoice</p>
</div>
<div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
      <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby</li>
      <li class="breadcrumb-item active" aria-current="page">Data Invoice</li>
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
      @if (auth()->user()->role == 'super_admin' || auth()->user()->role == 'admin')
      <a href={{route('data.product.create')}}>
        <div id="add" class="btn btn-success"><i class="ri-add-box-line me-2"></i>Tambah Invoice</div>
      </a>
      @endif
    </div>
    <div class="table-wrapper mt-2 mb-2">
      <table id="example" class="table mt-3 table-hover table-borderless" style="width: 100%">
        <thead>
          <tr>
            <th class="text-secondary">ID</th>
            <th class="text-secondary">Kode Invoice</th>
            <th class="text-secondary">Perusahaan</th>
            <th class="text-secondary">Nominal</th>
            <th class="text-secondary">Jatuh Tempo</th>
            <th class="text-secondary">Status Pelunasan</th>
            <th class="text-secondary">Nominal DP</th>
            <th class="text-secondary">Jatuh Tempo DP</th>
            <th class="text-secondary">Status Dp</th>
            <th class="text-secondary">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @foreach ($invoices as $invoice)
          <tr>
            <td>{{$invoice->id }}</td>
            <td>#{{$invoice->invoice_code }}</td>
            <td>{{$invoice->company->name }}</td>
            <td>Rp{{number_format($invoice->amount,0, ".", ".")}}</td>
            <td>{{date("Y-m-d", strtotime($invoice->payment_due_date)) }}</td>
            <td>{{$invoice->status == 1 ? 'Paid' : 'Unpaid'}}</td>
            <td>Rp{{number_format($invoice->dp_value,0, ".", ".")}}</td>
            <td>{{ $invoice->dp_value > 0 ? ($invoice->dp_due_date->format('d-m-Y')) : '-' }}</td>
            <td>{{$invoice->dp_value > 0 ? ($invoice->dp_status ? 'Terbayar' : 'Belum Terbayar') : '-'}}</td>
            <td class="">
              <div class="btn-wrapper d-flex gap-2 flex-wrap">
                <div data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="Edit data invoice"
                  class="btn edit btn-action
                  btn-warning
                  text-white"><i class="bx bx-edit"></i></div>
                <a href={{route('data-invoice.show', $invoice->id)}} data-bs-toggle="tooltip"
                  data-bs-custom-class="custom-tooltip"
                  data-bs-title="Detail Invoice" class="btn detail btn-action
                  btn-primary
                  text-white"><i class="ri-list-check"></i></a>
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
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Hapus Invoice</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4 class="text-center">Apakah anda yakin mengapus Invoice <span class="invoice-code"></span>?</h4>
      </div>
      <form action=# method="post" id="deleteForm">
        @method('delete')
        @csrf
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
  $(document).on('click', '.delete-btn', function(event){
        let invoiceCode = $(this).data('invoice-code');
        let deleteLink = $(this).data('delete-link');

        $('#deleteModal').modal('show');
        $('.invoice-code').html(invoiceCode);

        $('#deleteForm').attr('action', deleteLink);
      });   
</script>
@endpush

@endsection