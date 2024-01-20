@extends('layouts.base')
@section('content')
{{ $slot }}
@endsection
@livewireScripts
<script>
     document.addEventListener('livewire:init', () => {
       Livewire.on('success', (event) => {
        Toast.fire({
            icon: 'success',
            title: 'Success!',
            text: event.message,
            })  
       });
       
       Livewire.on('warning', (event) => {
        Toast.fire({
            icon: 'warning',
            title: 'Warning!',
            text: event.message,
            })  
       });

       Livewire.on('endLoad', (event) => {
        endLoading();
       });

});

  @if (session()->has('success'))
            Toast.fire({
            icon: 'success',
            title: "{{session('success')}}",
            })  
            @endif

            @if (session()->has('error'))
            Toast.fire({
            icon: 'error',
            title: "{{session('error')}}",
            })  
            @endif

</script>