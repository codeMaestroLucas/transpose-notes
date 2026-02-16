<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $songTitle ?: 'Notas Transpostas' }}</title>
    <style>
        /* ============================================================
           CSS para PDF — replica o visual dos cards da página index
           Orientação: retrato (A4 portrait)
           ============================================================ */

        /* ---------- Fontes ---------- */
        @font-face {
            font-family: 'Chewy';
            src: url('{{ public_path('fonts/Chewy/Chewy-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Bai Jamjuree';
            src: url('{{ public_path('fonts/Bai_Jamjuree/BaiJamjuree-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Bai Jamjuree';
            src: url('{{ public_path('fonts/Bai_Jamjuree/BaiJamjuree-Medium.ttf') }}') format('truetype');
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: 'SN Pro';
            src: url('{{ public_path('fonts/SN_Pro/static/SNPro-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'SN Pro';
            src: url('{{ public_path('fonts/SN_Pro/static/SNPro-Bold.ttf') }}') format('truetype');
            font-weight: 700;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'SN Pro', 'DejaVu Sans', Arial, sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        /* ---------- Cada tonalidade em uma página ---------- */
        .tone-page {
            page-break-after: always;
            width: 100%;
            height: 100%;
            display: table;
            background-image: url('{{ public_path('assets/images/bg_pdf.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .tone-page:last-child {
            page-break-after: auto;
        }

        .tone-page-inner {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 180px 80px 100px;
        }

        /* ---------- Card container ---------- */
        .tone-card {
            border-radius: 16px;
            border: 0.8px solid black;
            background: rgba(255, 253, 253, 0.92);
            min-width: 70%;
            max-width: 80%;
            height: 70%;
            margin: 0 auto;
        }

        /* ---------- Header ---------- */
        .tone-header {
            border-radius: 15px 15px 0 0;
            padding: 24px 30px 16px;
            text-align: center;
        }

        /* ---------- Título da música (Chewy) ---------- */
        .song-title {
            font-family: 'Chewy', cursive;
            font-size: 36px;
            font-weight: 400;
            color: black;
            text-align: center;
            margin-bottom: 4px;
        }

        /* ---------- Subtítulo: tonalidade + instrumentos (Bai Jamjuree) ---------- */
        .tone-subtitle {
            font-family: 'Bai Jamjuree', sans-serif;
            font-size: 20px;
            font-weight: 500;
            opacity: 0.85;
            text-align: center;
            color: #bb0d0d;
        }

        /* ---------- Container de notas (corpo do card) ---------- */
        .notes-display {
            padding: 30px 20px;
            text-align: center;
        }

        /* ---------- Linha de notas ---------- */
        .notes-line {
            text-align: center;
            margin-bottom: 10px;
        }

        .notes-line--empty {
            height: 20px;
        }

        /* ---------- Badge de nota (SN Pro) ---------- */
        .note-badge {
            font-family: 'SN Pro', sans-serif;
            display: inline-block;
            min-width: 70px;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 26px;
            font-weight: 700;
            color: black;
            text-align: center;
            margin: 4px 2px;
        }

        /* ---------- Sem notas ---------- */
        .empty-msg {
            font-family: 'Bai Jamjuree', sans-serif;
            font-size: 22px;
            color: #999;
            text-align: center;
            padding: 40px;
        }
    </style>
</head>
<body>
    @foreach ($scales as $index => $scale)
        @php
            $key = match($index) {
                0 => 'do',
                1 => 'mib',
                2 => 'sib',
            };
        @endphp
        <div class="tone-page">
            <div class="tone-page-inner">
            <div class="tone-card">
                <div class="tone-header">
                    @if ($songTitle)
                        <div class="song-title">{{ $songTitle }}</div>
                    @endif
                    <div class="tone-subtitle">{{ $scale['label'] }}</div>
                </div>
                <div class="notes-display">
                    @forelse ($scale['lines'] as $line)
                        @if (empty($line))
                            <div class="notes-line--empty"></div>
                        @else
                            <div class="notes-line">
                                @foreach ($line as $note)
                                    <span class="note-badge note-badge--{{ $key }}">{{ $note }}</span>
                                @endforeach
                            </div>
                        @endif
                    @empty
                        <span class="empty-msg">Nenhuma nota informada</span>
                    @endforelse
                </div>
            </div>
            </div>
        </div>
    @endforeach
</body>
</html>
