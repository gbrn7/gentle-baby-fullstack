@if ($form)
<!-- Edit Modal -->
<form action={{route('data.admin.pelanggan.update')}} enctype="multipart/form-data" id="addForm" method="POST">
  @method('put')
  @csrf
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">{{$form->id === auth()->user()->id ? 'Profile Saya' : 'Edit Admin'}}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" value="{{$form->id}}" id="edit-id">
        <div class="img-wrapper d-flex justify-content-center">
          <img src={{asset('Storage/avatar/'.($form->image_profile ? $form->image_profile : 'default.png'))}}
          class="img-fluid img-avatar-update ">
        </div>
        <div class="form-group mb-3">
          <label for="name" class="mb-1 text-left">Nama</label>
          <input value="{{$form->name}}" required class="form-control" type="text" name="name" id="name"
            placeholder="Masukkan nama admin" />
        </div>
        <div class="form-group mb-3">
          <label for="email" class="mb-1">Email</label>
          <input value="{{$form->email}}" required class="form-control" type="email" name="email" id="email"
            placeholder="Masukkan email admin" />
        </div>
        <div class="form-group pass-wrapper mb-3">
          <label for="password" class="mb-1">Password</label>
          <div class="input-group mb-3">
            <input value="{{$form->password}}" required class="form-control pass-input" type="password" name="password"
              id="password" placeholder="Masukkan password admin" />
            <span class="input-group-text pass-button" id="basic-addon2" onclick="showPass()"><i
                class="ri-eye-fill"></i></span>
          </div>
        </div>
        <div class="form-group mb-3">
          <label for="phone_number" class="mb-1">Nomor Telepon</label>
          <input value="{{$form->phone_number}}" class="form-control" type="phone_number" name="phone_number"
            id="phone_number" placeholder="Masukkan nomor telepon" />
        </div>
        <div class="form-group mb-3">
          <label for="formFile" class="form-label">Perbarui Foto Profil</label>
          <input class="form-control" type="file" name="image_profile" id="profile" onchange="updateImageHandler(this)">
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
<script>
  function showPass(event) {
        if($('.pass-wrapper input').attr("type") == "text"){
            $('.pass-wrapper input').attr('type', 'password');
            $('.pass-wrapper i').addClass( "ri-eye-off-fill" );
            $('.pass-wrapper i').removeClass( "ri-eye-fill" );
        }else if($('.pass-wrapper input').attr("type") == "password"){
            $('.pass-wrapper input').attr('type', 'text');
            $('.pass-wrapper i').removeClass( "ri-eye-fill" );
            $('.pass-wrapper i').addClass( "ri-eye-off-fill" );
        }    
  }
</script>
@else
<!-- Info Modal -->
<div class="modal-dialog ">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel">Informasi</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
      <p class="text">Data Admin Tidak Ditemukan!!!</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
    </div>
  </div>
</div>
@endif