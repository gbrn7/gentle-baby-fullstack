{{-- Create Modal --}}
<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Tambah Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="img-wrapper d-flex justify-content-center">
          <img src={{asset('Storage/avatar/default.png')}} class="img-fluid img-avatar-create ">
        </div>
        <form action="#" id="addForm" method="POST">
          @csrf
          <div class="form-group mb-3">
            <label for="name" class="mb-1">Nama</label>
            <input required class="form-control" type="text" name="name" id="name" placeholder="Masukkan nama admin" />
          </div>
          <div class="form-group mb-3">
            <label for="email" class="mb-1">Email</label>
            <input required class="form-control" type="email" name="email" id="email"
              placeholder="Masukkan email admin" />
          </div>
          <div class="form-group mb-3">
            <label for="password" class="mb-1">Password</label>
            <input required class="form-control" type="password" name="email" id="password"
              placeholder="Masukkan password admin" />
          </div>
          <div class="form-group mb-3">
            <label for="Status" class="mb-1">Role</label>
            <select required id="benefited" name="benefited" class="form-select status"
              aria-label="Default select example">
              <option class="text-secondary" value="">
                Klik untuk memilih Role
              </option>
              <option value="admin" class="text-secondary">Admin</option>
              <option value="super_admin" class="text-secondary">Super Admin</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="phone_number" class="mb-1">Nomor Telepon</label>
            <input class="form-control" type="phone_number" name="phone_number" id="phone_number"
              placeholder="Masukkan nomor telepon" />
          </div>
          <div class="form-group mb-3">
            <label for="formFile" class="form-label">Foto Profil</label>
            <input class="form-control" type="file" name="image" id="profile">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <form action="#" id="editForm" method="POST">
    @method('put')
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">Edit Admin</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Hapus Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h4 class="text-center">Apakah anda yakin mengapus admin <span class="criteria-name"></span>?</h4>
      </div>
      <form action="#" method="post">
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
       
      $(document).on('click', '.edit', function (event){
          event.preventDefault();
          var id = $(this).data('id');
          var name = $(this).data('name');
          var weight = $(this).data('weight');
          var benefited = $(this).data('benefited');
          $('#editmodal').modal('show');
          $('#name-edit').val(name);
          $('#weight-edit').val(weight);
          $('#benefited-edit').val(benefited);
          $('#edit-id').val(id);
      });
       
      $(document).on('click', '.delete', function(event){
          event.preventDefault();
          var id = $(this).data('id');
          var name = $(this).data('name');
          $('#deletemodal').modal('show');
          $('#delete-id').val(id);
          $('.criteria-name').html(name);
      });

  $("#profile").change(function(input) {
    console.log('first')
    console.log(input.originalEvent.srcElement.files[0]);
      if (input.originalEvent.srcElement.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('.img-avatar-create').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.originalEvent.srcElement.files[0]);
    }
  });       
  });

    
</script>
@endpush