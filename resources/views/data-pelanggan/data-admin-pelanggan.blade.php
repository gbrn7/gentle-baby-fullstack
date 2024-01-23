@extends('layouts.base')

@section('content')
<div class="title-box  d-flex gap-2 align-items-baseline"><i class="ri-team-line fs-2"></i>
  <p class="fs-3 m-0">Data Admin Pelanggan {{$admins[0]->company->name}}</p>
</div>
<div class="breadcrumbs-box mt-2 rounded rounded-2 bg-white p-2">
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
      <li class="breadcrumb-item d-flex gap-2 align-items-center"><i class="ri-apps-line"></i>Baby Gentle</li>
      <li class="breadcrumb-item"><a href={{route('data.pelanggan')}} class="text-decoration-none">Data Pelanggan</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">Data Admin Pelanggan {{$admins[0]->company->name}}</li>
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
      <div id="add-admin" data-bs-toggle="modal" data-id="{{$admins[0]->company->id}}" data-bs-target="#addnew"
        class="btn btn-success"><i class="ri-add-box-line me-2"></i>Tambah Admin</div>
    </div>
    <div class="table-wrapper mt-2 mb-2">
      <table id="example" class="table mt-3 table-hover table-borderless">
        <thead>
          <tr>
            <th class="text-secondary">ID</th>
            <th class="text-secondary">Nama</th>
            <th class="text-secondary">Email</th>
            <th class="text-secondary">Role</th>
            <th class="text-secondary">Nomor Telepon</th>
            <th class="text-secondary">Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          @foreach ($admins as $admin)
          <tr>
            <td>{{$admin->user->id}}</td>
            <td>{{$admin->user->name}}</td>
            <td>{{$admin->user->email}}</td>
            <td>{{$admin->user->role === 'super_admin' ? 'Super Admin' : 'Admin'}}</td>
            <td>{{$admin->user->phone_number}}</td>
            <td class="">
              <div class="btn-wrapper d-flex gap-2 flex-wrap">
                <a href="#" data-id="{{$admin->user->id}}" data-bs-toggle="tooltip"
                  data-bs-custom-class="custom-tooltip" data-bs-title="Perbarui data admin"
                  data-name="{{$admin->user->name}}" class="btn edit btn-action btn-warning text-white"><i
                    class="bx bx-edit"></i></a>
                <a href="#" class="delete btn btn-action btn-danger text-white" data-bs-toggle="tooltip"
                  data-bs-custom-class="custom-tooltip" data-bs-title="Hapus data admin"
                  data-name="{{$admin->user->name}}" data-id="{{$admin->user->id}}">
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
@include('modal.data-pelanggan.data-admin-pelanggan-modal')

@endsection