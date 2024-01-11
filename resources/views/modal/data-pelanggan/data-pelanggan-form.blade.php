@if ($form)
<!-- Edit Modal -->
<form action={{route('data.pelanggan.update')}} enctype="multipart/form-data" id="addForm" method="POST">
  @method('put')
  @csrf
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Edit Perusahaan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" value="{{$form->id}}" id="edit-id">
        <div class="form-group mb-3">
          <label for="name" class="mb-1">Nama Perusahaan</label>
          <input value="{{$form->name}}" required class="form-control" type="text" name="name" id="name"
            placeholder="Masukkan nama perusahaan pelanggan" />
        </div>
        <div class="form-group mb-3">
          <label for="email" class="mb-1">Email Perusahaan</label>
          <input value="{{$form->email}}" class="form-control" type="email" name="email" id="email"
            placeholder="Masukkan email perusahaan pelanggan" />
        </div>
        <div class="form-group mb-3">
          <label for="phone_number" class="mb-1">Alamat Perusahaan</label>
          <input value="{{$form->address}}" class="form-control" type="text" name="address" id="address"
            placeholder="Masukkan alamat perusahaan pelanggan" />
        </div>
        <div class="form-group mb-3">
          <label for="phone_number" class="mb-1">Nomor Telepon Perusahaan</label>
          <input value="{{$form->phone_number}}" class="form-control" type="phone_number" name="phone_number"
            id="phone_number" placeholder="Masukkan nomor telepon perusahaan pelanggan" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-warning text-white">Perbarui</button>
      </div>
    </div>
  </div>
  </div>
</form>
@else
<!-- Info Modal -->
<div class="modal-dialog ">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel">Informasi</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      <p class="text">Data Pelanggan Tidak Ditemukan!!!</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
  </div>
</div>
@endif