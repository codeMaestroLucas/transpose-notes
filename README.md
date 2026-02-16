# Transpositor de Notas

Ferramenta web para transposição de notas musicais voltada para educação musical infantil. Permite que educadores digitem notas em Dó (tom de concerto) e visualizem instantaneamente a transposição para instrumentos em Mib e Sib.

## Tonalidades Suportadas

| Tonalidade | Instrumentos | Transposição |
|------------|-------------|--------------|
| **Dó** | Flauta, Escaleta | Tom de concerto (0 semitons) |
| **Mib** | Sax Alto, Clarinata Alto | +9 semitons |
| **Sib** | Trompete, Clarinete, Sax Soprano | +2 semitons |

## Funcionalidades

- Transposição automática em tempo real com debounce
- Suporte a marcadores de notas: `_` (sustentada), `↓` (oitava abaixo), `↑` (oitava acima)
- Linhas vazias preservadas como espaçadores visuais
- Exportação para PDF com layout em cards (orientação retrato)
- Download direto do PDF com notificação via toast
- Navegação por Tab do título para o campo de notas

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2
- **Frontend:** Bootstrap 5 (SCSS), Alpine.js (CDN)
- **Bundler:** Vite
- **PDF:** barryvdh/laravel-dompdf
- **Fontes:** Chewy (títulos), Bai Jamjuree (subtítulos), SN Pro (notas/corpo)

## Instalação

```bash
# Dependências PHP
composer install

# Dependências frontend
npm install

# Configuração
cp .env.example .env
php artisan key:generate

# Diretório de cache de fontes do DomPDF
mkdir -p storage/fonts

# Build dos assets
npm run build
```

## Desenvolvimento

```bash
# Servidor Laravel
php artisan serve

# Vite dev server (em outro terminal)
npm run dev
```

Acesse `http://localhost:8000`.

## Rotas

| Método | URI | Descrição |
|--------|-----|-----------|
| GET | `/` | Página principal com os cards de transposição |
| POST | `/transpose` | API de transposição (retorna JSON) |
| POST | `/pdf` | Gera e baixa o PDF com as notas transpostas |

## Estrutura

```
app/
  Http/Controllers/
    TransposeController.php    # Controller principal (index, transpose, pdf)
  Music/
    MusicalScale.php           # Classe abstrata (escala cromática, normalização, transposição)
    DoMaior.php                # Dó - 0 semitons
    MibMaior.php               # Mib - 9 semitons
    SibMaior.php               # Sib - 2 semitons
resources/
  scss/
    app.scss                   # Entry point SCSS
    _cards.scss                # Estilização dos cards
    _fonts.scss                # Referência de fontes (declarações em Blade)
  views/
    layouts/app.blade.php      # Layout base (@font-face via asset(), background)
    transpose/
      index.blade.php          # Página principal (Alpine.js)
      pdf.blade.php            # Template do PDF (DomPDF)
public/
  fonts/                       # Chewy, Bai_Jamjuree, SN_Pro (.ttf)
  assets/images/               # Imagens de background (site e PDF)
```

## Licença

[MIT](https://opensource.org/licenses/MIT)
