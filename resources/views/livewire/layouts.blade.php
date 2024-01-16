@extends('layouts.base')
@section('content')
{{ $slot }}
@endsection
@push('js')
<script>
  const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            })


            @if (session()->has('success'))
            console.log('first')
            Toast.fire({
            icon: 'success',
            title: "{{session('success')}}",
            })  
            @endif

            @if (session()->has('error'))
            console.log('first')
            Toast.fire({
            icon: 'error',
            title: "{{session('error')}}",
            })  
            @endif
</script>
@endpush