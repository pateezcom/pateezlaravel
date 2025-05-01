<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pateez Haber</title>
    <meta name="description" content="En güncel ve doğru haberler">
    <meta name="keywords" content="haber, son dakika, güncel haberler">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">
    
    <!-- Custom CSS -->
    <style>
        .navbar-toggler:focus {
            box-shadow: none;
        }
        .ti {
            font-size: 1.25rem;
            vertical-align: middle;
        }
    </style>

    @vite('resources/js/frontend/app.js')
</head>
<body>
    <div id="app"
        @if(isset($verified))
        data-verified="true"
        @endif
        @if(isset($error_message))
        data-error-message="{{ $error_message }}"
        @endif
    ></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const app = document.getElementById('app');
            
            // E-posta doğrulama başarılı mesajı
            if (app.dataset.verified) {
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: 'E-posta adresiniz başarıyla doğrulandı.',
                    confirmButtonText: 'Tamam'
                });
            }
            
            // E-posta doğrulama hata mesajı
            if (app.dataset.errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: app.dataset.errorMessage,
                    confirmButtonText: 'Tamam'
                });
            }
        });
    </script>
</body>
</html>
