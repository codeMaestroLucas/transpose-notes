<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Transpositor de Notas')</title>

    {{-- Alpine.js via CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Fontes locais via asset() — garante resolução correta em dev (Vite) e produção --}}
    <style>
        /* ---------- Chewy (títulos) ---------- */
        @font-face {
            font-family: 'Chewy';
            src: url('{{ asset('fonts/Chewy/Chewy-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        /* ---------- Bai Jamjuree (subtítulos) ---------- */
        @font-face {
            font-family: 'Bai Jamjuree';
            src: url('{{ asset('fonts/Bai_Jamjuree/BaiJamjuree-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Bai Jamjuree';
            src: url('{{ asset('fonts/Bai_Jamjuree/BaiJamjuree-Medium.ttf') }}') format('truetype');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Bai Jamjuree';
            src: url('{{ asset('fonts/Bai_Jamjuree/BaiJamjuree-SemiBold.ttf') }}') format('truetype');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Bai Jamjuree';
            src: url('{{ asset('fonts/Bai_Jamjuree/BaiJamjuree-Bold.ttf') }}') format('truetype');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }

        /* ---------- SN Pro (notas e corpo) ---------- */
        @font-face {
            font-family: 'SN Pro';
            src: url('{{ asset('fonts/SN_Pro/static/SNPro-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'SN Pro';
            src: url('{{ asset('fonts/SN_Pro/static/SNPro-Medium.ttf') }}') format('truetype');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'SN Pro';
            src: url('{{ asset('fonts/SN_Pro/static/SNPro-SemiBold.ttf') }}') format('truetype');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'SN Pro';
            src: url('{{ asset('fonts/SN_Pro/static/SNPro-Bold.ttf') }}') format('truetype');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'SN Pro';
            src: url('{{ asset('fonts/SN_Pro/static/SNPro-ExtraBold.ttf') }}') format('truetype');
            font-weight: 800;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'SN Pro';
            src: url('{{ asset('fonts/SN_Pro/static/SNPro-Black.ttf') }}') format('truetype');
            font-weight: 900;
            font-style: normal;
            font-display: swap;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-image: url('{{ asset('assets/images/bg_site.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="container-fluid py-4">
        @yield('content')
    </div>
</body>
</html>
