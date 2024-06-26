@extends('layouts.base-auth')

@section('asset-image')
<img src="{{asset('Assets/Img/login-thumb.jpg')}}" class="login-img object-fit-cover" />
@endsection

@section('title', 'Sign In')

@section('content')
<form action={{route('sign-in.auth')}} method="post">
  @csrf
  <div class="login-form d-flex flex-column gap-1 gap-lg-2 mt-2 mt-lg-4 mt-4">
    <label for="email">Email</label>
    <input name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror text-black"
      id="email" placeholder="Masukan email" />
    @error('email')
    <div class="invalid-feedback">
      {{$message}}
    </div>
    @enderror
    <div class="password-container position-relative">
      <label for="password">Password</label>
      <div class="pass-wrapper">
        <input name="password" type="password" class="form-control text-black @error('password') is-invalid @enderror"
          id="password" placeholder="Masukan password" />
        <i class="ri-eye-close-fill pass-icon eye-pass position-absolute"></i>
        @error('password')
        <div class="invalid-feedback">
          {{$message}}
        </div>
        @enderror
      </div>
    </div>
    <button class="btn btn-dark login-btn mt-1 mt-lg-2" type="submit">
      Sign In
    </button>
  </div>
</form>
@endsection