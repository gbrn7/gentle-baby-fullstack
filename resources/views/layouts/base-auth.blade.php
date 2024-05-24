<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="Assets/Css/Login style/main.css" />
  <title>Gentle Baby | Sign-In</title>

  {{-- Icon --}}
  <link rel="icon" href="{{asset('Assets/Img/apps-line.png')}}" type="image/x-icon">

  <!-- Bootrsrap Css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="style/signinStyle.css" />

  <!-- Link Remixicon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"
    integrity="sha512-OQDNdI5rpnZ0BRhhJc+btbbtnxaj+LdQFeh0V9/igiEPDiWE2fG+ZsXl0JEH+bjXKPJ3zcXqNyP4/F/NegVdZg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- Style --}}
  <link rel="stylesheet" href="{{asset('Assets/Css/signinStyle.css')}}">
</head>

<body>
  {{-- Sweet alert --}}
  @include('sweetalert::alert')
  <section class="login d-flex justify-content-center justify-content-lg-between">
    <div class="login-left w-50 h-100 d-none d-lg-block">
      <div class="row justify-content-center align-items-center h-100">
        <div class="col-4">
          @yield('asset-image')
        </div>
      </div>
    </div>
    <div class="login-right w-50 w-sm-75 h-100">
      <div class="row justify-content-center align-items-center h-100">
        <div class="col-12 border border-2 signin-box p-3 p-sm-4 rounded rounded-5 col-lg-6">
          <div class="header">
            <div class="text-center">
              <h1 class="my-0 mt-lg-3">@yield('title')</h1>
            </div>

          </div>
          @yield('content')
          <div class="auth-footer text-center text-secondary mt-2">
            <span>Copyright ©{{date('Y')}}, Gentle Baby</span>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Boostrap Js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
  </script>
  {{-- jquery --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer">
  </script>

  <script>
    $('.pass-icon').click(function (e) { 
    e.preventDefault();
    $('.pass-icon').toggleClass('ri-eye-fill');
    $('#password').prop("type") == 'password' ?  $('#password').attr('type', 'text') :  $('#password').attr('type', 'password'); 
  });
  </script>
</body>

</html>