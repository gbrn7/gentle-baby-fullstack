{{-- Create Modal --}}
<div class="modal fade" id="addnew" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Tambah Perusahaan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action={{route('data.pelanggan.store')}} id="addForm" method="POST">
          @csrf
          <div class="form-group mb-3">
            <label for="name" class="mb-1">Nama Pemilik</label>
            <input value="{{old('name')}}" required class="form-control" type="text" name="name" id="name"
              placeholder="Masukkan nama pemilik" />
          </div>
          <div class="form-group mb-3">
            <label for="email" class="mb-1">Email Pemilik</label>
            <input value="{{old('email')}}" required class="form-control" type="email" name="email" id="email"
              placeholder="Masukkan email pemilik" />
          </div>
          <div class="form-group mb-3">
            <label for="password" class="mb-1">Password Pemilik</label>
            <input required class="form-control" type="password" name="password" id="password"
              placeholder="Masukkan password pemilik" />
          </div>
          <div class="form-group mb-3">
            <label for="email" class="mb-1">Nomor Telepon Pemilik</label>
            <input value="{{old('phone_number')}}" required class="form-control" type="phone_number" name="phone_number"
              id="phone_number" placeholder="Masukkan nomor telepon pemilik" />
          </div>
          <div class="form-group mb-3">
            <label for="name" class="mb-1">Nama Perusahaan</label>
            <input value="{{old('name_company')}}" required class="form-control" type="text" name="name_company"
              id="name_company" placeholder="Masukkan nama perusahaan pelanggan" />
          </div>
          <div class="form-group mb-3">
            <label for="email" class="mb-1">Email Perusahaan</label>
            <input value="{{old('email_company')}}" class="form-control" type="email" name="email_company"
              id="email_company" placeholder="Masukkan email perusahaan pelanggan" />
          </div>
          <div class="form-group mb-3">
            <label for="phone_number" class="mb-1">Alamat Perusahaan</label>
            <input value="{{old('address_company')}}" class="form-control" type="text" name="address_company"
              id="address_company" placeholder="Masukkan alamat perusahaan pelanggan" />
          </div>
          <div class="form-group mb-3">
            <label for="phone_number" class="mb-1">Nomor Telepon Perusahaan</label>
            <input value="{{old('phone_number_company')}}" class="form-control" type="phone_number_company"
              name="phone_number_company" id="phone_number_company"
              placeholder="Masukkan nomor telepon perusahaan pelanggan" />
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
          <div class="spinner-border text-black" role="status">
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
        <h4 class="text-center">Apakah anda yakin mengapus pelanggan <span class="criteria-name"></span>?</h4>
      </div>
      <form action={{route('data.pelanggan.delete')}} method="post">
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
          $('#delete-id').val(id);
          $('.criteria-name').html(name);
      });  

  });

  function imageHandler(input) {
    const img = document.querySelector('.img-avatar-create')
    const file =input.files[0]; 
    let url = window.URL.createObjectURL(file);

    img.src =url;
  };

  function updateImageHandler(input) {
    const img = document.querySelector('.img-avatar-update')
    const file =input.files[0]; 
    let url = window.URL.createObjectURL(file);

    img.src =url;
  };      

  $(document).on('click', '.edit', function (event){
          var id = $(this).data('id');
          event.preventDefault();
          $('#editmodal').modal('show');
          getDataCompanyForm(id);
      });

  function getDataCompanyForm(id){
    $.get("{{ route('data.pelanggan.getForm') }}/",{id:id}, function(data){
        $('#editmodal').empty().html(data);
    })
  }

</script>
@endpush