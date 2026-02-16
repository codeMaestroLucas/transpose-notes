<?php

namespace App\Http\Controllers;

use App\Music\DoMaior;
use App\Music\MibMaior;
use App\Music\MusicalScale;
use App\Music\SibMaior;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransposeController extends Controller
{
    public function index()
    {
        $scales = [
            'do'  => new DoMaior(),
            'mib' => new MibMaior(),
            'sib' => new SibMaior(),
        ];

        return view('transpose.index', compact('scales'));
    }

    /**
     * Extrai e normaliza as notas do input preservando a estrutura de linhas.
     * Linhas vazias são preservadas como arrays vazios (espaçamento visual).
     *
     * Input:
     *   "do re mi\n\nfa sol\nla si"
     *
     * Output:
     *   [['Dó', 'Ré', 'Mi'], [], ['Fá', 'Sol'], ['Lá', 'Si']]
     */
    private function parseNotesByLine(string $input): array
    {
        $lines = preg_split('/\r?\n/', $input);
        $result = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Linha vazia → preserva como array vazio (espaçamento)
            if ($trimmed === '') {
                $result[] = [];
                continue;
            }

            $raw = array_filter(
                array_map('trim', preg_split('/[\s,;|]+/', $trimmed)),
                fn ($n) => $n !== ''
            );

            $result[] = MusicalScale::normalizeAll(array_values($raw));
        }

        // Remove linhas vazias no final (trailing)
        while (!empty($result) && $result[array_key_last($result)] === []) {
            array_pop($result);
        }

        return $result;
    }

    public function transpose(Request $request)
    {
        $lines = $this->parseNotesByLine($request->input('notes', ''));

        $do  = new DoMaior();
        $mib = new MibMaior();
        $sib = new SibMaior();

        return response()->json([
            'do'  => array_map(fn ($line) => $do->transpose($line), $lines),
            'mib' => array_map(fn ($line) => $mib->transpose($line), $lines),
            'sib' => array_map(fn ($line) => $sib->transpose($line), $lines),
        ]);
    }

    public function pdf(Request $request)
    {
        $lines     = $this->parseNotesByLine($request->input('notes', ''));
        $songTitle = trim($request->input('song_title', ''));

        $do  = new DoMaior();
        $mib = new MibMaior();
        $sib = new SibMaior();

        $scales = [
            [
                'label' => $do->label(),
                'color' => $do->color(),
                'lines' => array_map(fn ($line) => $do->transpose($line), $lines),
            ],
            [
                'label' => $mib->label(),
                'color' => $mib->color(),
                'lines' => array_map(fn ($line) => $mib->transpose($line), $lines),
            ],
            [
                'label' => $sib->label(),
                'color' => $sib->color(),
                'lines' => array_map(fn ($line) => $sib->transpose($line), $lines),
            ],
        ];

        $pdf = Pdf::loadView('transpose.pdf', compact('scales', 'songTitle'))
            ->setPaper('a4', 'portrait');

        $filename = $songTitle
            ? 'notas-' . Str::slug($songTitle) . '.pdf'
            : 'notas-transpostas.pdf';

        return $pdf->download($filename);
    }
}
