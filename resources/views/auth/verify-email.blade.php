@extends('layouts/layoutMaster')

@section('title', 'E-posta Doğrulama')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
<script>
  // Eğer mesaj varsa SweetAlert2 kullanarak göster
  document.addEventListener('DOMContentLoaded', function() {
    @if (session('message'))
      Swal.fire({
        icon: 'success',
        title: 'Başarılı!',
        text: '{{ session('message') }}',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
    @endif

    @if (session('verified'))
      Swal.fire({
        icon: 'success',
        title: 'Doğrulama Başarılı!',
        text: 'E-posta adresiniz başarıyla doğrulandı.',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
    @endif
  });
</script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="misc-wrapper">
    <h2 class="mb-1 mt-4">E-posta Adresinizi Doğrulayın ✉️</h2>
    <p class="mb-4 mx-2">
      Hesabınızı kullanmaya başlamadan önce, lütfen size gönderdiğimiz doğrulama bağlantısını tıklayarak e-posta adresinizi doğrulayın.
      <br>Eğer e-postayı almadıysanız, size yeni bir doğrulama e-postası göndermemiz için aşağıdaki butona tıklayabilirsiniz.
    </p>

    <div class="d-flex justify-content-center mt-5">
      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Doğrulama E-postasını Tekrar Gönder</button>
      </form>
    </div>

    <div class="d-flex justify-content-center mt-4">
      <a href="{{ route('frontend.home') }}" class="btn btn-label-secondary">Ana Sayfaya Dön</a>
    </div>
  </div>
</div>
@endsection