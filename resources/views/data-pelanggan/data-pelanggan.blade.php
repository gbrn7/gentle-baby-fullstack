@extends('layouts.base')

@section('content')
<div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-team-line fs-2"></i>
  <p class="fs-3 m-0">Data Perusahaan</p>
</div>
<div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
      <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Gentle Baby</li>
      <li class="breadcrumb-item active" aria-current="page">Data Perusahaan</li>
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
      <div id="add" data-bs-toggle="modal" data-bs-target="#addnew" class="btn btn-success"><i
          class="ri-add-box-line me-2"></i>Tambah Perusahaan</div>
    </div>

    <div class="table-wrapper mt-2 mb-2">
      <table id="example" class="table mt-3 table-hover table-borderless" style="width: 100%">
        <thead>
          <tr>
            <th class="text-secondary">ID</th>
            <th class="text-secondary">Nama</th>
            <th class="text-secondary">Email</th>
            <th class="text-secondary">Alamat</th>
            <th class="text-secondary">Nomor Telepon</th>
            <th class="text-secondary">Nama Pemilik</th>
            <th class="text-secondary">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @foreach ($companies as $company)
          <tr>
            <td>{{$company->id ? $company->id : '-' }}</td>
            <td>{{$company->name ? $company->name : '-' }}</td>
            <td>{{$company->email ? $company->email : '-' }}</td>
            <td>{{$company->address ? $company->address : '-' }}</td>
            <td>{{$company->phone_number ? $company->phone_number : '-' }}</td>
            <td>{{$company->owner->name ? $company->owner->name : '-' }}</td>
            <td class="">
              <div class="btn-wrapper d-flex gap-2 flex-wrap">
                <a href="#" data-id="{{$company->id}}" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip"
                  data-bs-title="Perbarui data perusahaan" data-name="{{$company->name}}"
                  class="btn edit btn-action btn-warning text-white"><i class="bx bx-edit"></i></a>
                <a type="button" href={{route('data.admin.pelanggan', $company->id)}} class="btn btn-secondary"
                  data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="Data admin pelanggan">
                  <i class="ri-list-check"></i>
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
@include('modal.data-pelanggan.data-pelanggan-modal')

@endsection