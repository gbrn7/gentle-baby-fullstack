<nav class="navbar navbar-expand-md">
  <div class="container-fluid">
    <div class="d-flex justify-content-between  d-block">
      <a class="navbar-brand fs-5" href="#">Gentle Baby</a>
      <button class="btn px-1 py-0 open-btn">
        <i class="fas fa-stream"></i>
      </button>
    </div>
    <div class="profile-wrap">
      <div class="dropdown me-2 d-inline d-md-none">
        <a class="btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="theme-icon @if (session()->get('mode') === 'darkMode')
            ri-moon-clear-line
          @else
            ri-sun-line
          @endif"></i> </a>
        <ul class="dropdown-menu dropdown-menu-end px-2">
          <li class="dropdown-item rounded-2 light-mode-btn">Light</li>
          <li class="dropdown-item rounded-2 dark-mode-btn">Dark</li>
          <li class="dropdown-item rounded-2 system-mode-btn">System</li>
        </ul>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse justify-content-end gap-3" id="navbarSupportedContent">
      <div class="dropdown">
        <a class="nav-link d-flex gap-2 pt-3 pt-md-0 align-items-center justify-content-end dropdown-toggle" href="#"
          role="button" aria-current="page" data-bs-toggle="dropdown" aria-expanded="false">
          <p class="my-0">{{auth()->user()->name}}</p>
          <img src={{asset('Storage/avatar/'.(auth()->user()->image_profile ? auth()->user()->image_profile :
          'default.png'))}}
          class="img-fluid img-avatar ">
        </a>
        <ul class="dropdown-menu dropdown-menu-end px-2">
          <li class="rounded-2 dropdown-list my-profile"><a class="dropdown-item rounded-2" href="#"><i
                class="ri-user-3-line me-2"></i>Profil Saya</a>
          </li>
          <li class="rounded-2 dropdown-list my-company-profile"><a class="dropdown-item rounded-2" href="#"><i
                class="ri-building-2-line me-2"></i>Profile Perusahaan</a>
          </li>
          <li class="rounded-2 dropdown-list"> <a href="{{route('logout')}}" class="dropdown-item rounded-2"><i
                class="ri-logout-circle-line me-2"></i>Sign
              Out</a>
          </li>
        </ul>
      </div>
      <div class="dropdown me-md-3 d-none d-md-block">
        <a class="btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="theme-icon @if (session()->get('mode') === 'darkMode')
            ri-moon-clear-line
          @else
          ri-sun-line
          @endif"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end px-2">
          <li class="dropdown-item rounded-2 light-mode-btn">Light</li>
          <li class="dropdown-item rounded-2 dark-mode-btn">Dark</li>
          <li class="dropdown-item rounded-2 system-mode-btn">System</li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" class="p-0">
  <form action="#" id="profileForm" method="POST">
    @method('put')
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">Profile Saya</h5>
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

<!-- Company Profile Modal -->
<div class="modal fade" id="myCompanyModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <form action="#" id="companyForm" method="POST">
    @method('put')
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">Profile Perusahaan</h5>
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


@push('js')
<script>
  $(document).on('click', '.my-profile', function (event){
          event.preventDefault();
          $('#profileModal').modal('show');

          $.get("{{route('client.getCurrentUser')}}")
          .done(function(data) {
              $('#profileModal').empty().html(data);
          })
          .fail(function(xhr, status, error) {
              console.log("Error: " + error);
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

  $(document).on('click', '.my-company-profile', function (event){
      event.preventDefault();
      $('#myCompanyModal').modal('show');

      $.get("{{route('data.perusahaan.current')}}")
      .done(function(data) {
          $('#myCompanyModal').empty().html(data);
      })
      .fail(function(xhr, status, error) {
          console.log("Error: " + error);
      });
  });

  $(document).ready(function () {
      $(".light-mode-btn").on('click', function(){
        lightMode();
      }); 

      $(".dark-mode-btn").on('click', function(){
        darkMode();
      });

      $(".system-mode-btn").on('click', function(){
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
          darkMode();
        }else{
          lightMode();
        }
      });
    });

    function lightMode(){
      $('.theme-icon').removeClass('ri-moon-clear-line').addClass('ri-sun-line');
      document.querySelector('body').classList.remove('dark');
      sessionStorage.setItem("mode", "light");
      $.get("{{ route('client.setModeSession') }}",{mode:"lightMode"}, function(res){
      })
    }

    function darkMode(){
      $('.theme-icon').removeClass('ri-sun-line').addClass('ri-moon-clear-line');
      document.querySelector('body').classList.add('dark');
      sessionStorage.setItem("mode", "dark");
      $.get("{{ route('client.setModeSession') }}",{mode:"darkMode"}, function(res){
      })
    }
</script>
@endpush