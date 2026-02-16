<?php

namespace App\Music;

abstract class MusicalScale
{
    protected const CHROMATIC_SCALE = [
        'Dó', 'Dó#', 'Ré', 'Ré#', 'Mi', 'Fá',
        'Fá#', 'Sol', 'Sol#', 'Lá', 'Lá#', 'Si',
    ];

    /**
     * Mapa de aliases: input do usuário (lowercase, sem acento) → nota canônica.
     * Permite que o usuário digite "do", "re", "fa#", "sol#" etc.
     */
    protected const NOTE_ALIASES = [
        'do'   => 'Dó',
        'do#'  => 'Dó#',
        'dó'   => 'Dó',
        'dó#'  => 'Dó#',
        're'   => 'Ré',
        're#'  => 'Ré#',
        'ré'   => 'Ré',
        'ré#'  => 'Ré#',
        'mi'   => 'Mi',
        'fa'   => 'Fá',
        'fa#'  => 'Fá#',
        'fá'   => 'Fá',
        'fá#'  => 'Fá#',
        'sol'  => 'Sol',
        'sol#' => 'Sol#',
        'la'   => 'Lá',
        'la#'  => 'Lá#',
        'lá'   => 'Lá',
        'lá#'  => 'Lá#',
        'si'   => 'Si',
    ];

    /**
     * Semitons a transpor em relação a Dó (tom de concerto).
     * Cada classe filha define seu próprio intervalo.
     */
    abstract protected function semitones(): int;

    /**
     * Nome da tonalidade para exibição.
     */
    abstract public function label(): string;

    /**
     * Cor CSS associada à tonalidade.
     */
    abstract public function color(): string;

    /**
     * Marcadores suportados que o usuário pode anexar às notas.
     * São preservados durante normalização e transposição.
     *
     *  _  = nota longa / sustentada
     *  ↓  = oitava abaixo
     *  ↑  = oitava acima
     */
    protected const MARKERS = ['_', '↓', '↑'];

    /**
     * Separa os marcadores (sufixos/prefixos) do corpo da nota.
     *
     * Ex: "do_"  → ['do',  '_']
     *     "↓sol" → ['sol', '↓']
     *     "re↑_" → ['re',  '↑_']
     *
     * @return array{0: string, 1: string} [nota_limpa, marcadores]
     */
    public static function extractMarkers(string $input): array
    {
        $markersPattern = implode('', array_map(fn ($m) => preg_quote($m, '/'), self::MARKERS));

        // Captura: (prefixos)(nota)(sufixos)
        if (preg_match('/^([' . $markersPattern . ']*)(.*?)([' . $markersPattern . ']*)$/u', $input, $matches)) {
            $prefix  = $matches[1];
            $bare    = $matches[2];
            $suffix  = $matches[3];
            return [$bare, $prefix . $suffix];
        }

        return [$input, ''];
    }

    /**
     * Normaliza o input do usuário para a nota canônica, preservando marcadores.
     *
     * Ex: "re_"  → "Ré_"
     *     "FA#↓" → "Fá#↓"
     *     "sol"  → "Sol"
     */
    public static function normalize(string $note): string
    {
        $note = trim($note);

        if ($note === '') {
            return $note;
        }

        [$bare, $markers] = self::extractMarkers($note);

        $lower = mb_strtolower($bare, 'UTF-8');

        if (isset(self::NOTE_ALIASES[$lower])) {
            return self::NOTE_ALIASES[$lower] . $markers;
        }

        if (in_array($bare, self::CHROMATIC_SCALE, true)) {
            return $bare . $markers;
        }

        return $note; // nota não reconhecida, devolve como está
    }

    /**
     * Normaliza um array inteiro de notas vindas do input.
     *
     * @param  string[]  $notes
     * @return string[]
     */
    public static function normalizeAll(array $notes): array
    {
        return array_map([self::class, 'normalize'], $notes);
    }

    /**
     * Transpõe um array de notas em Dó para a tonalidade desta classe.
     *
     * @param  string[]  $notes  Notas já normalizadas (ex: ['Dó', 'Mi_', 'Sol↓'])
     * @return string[]  Notas transpostas (com marcadores preservados)
     */
    public function transpose(array $notes): array
    {
        return array_map(fn (string $note) => $this->transposeNote($note), $notes);
    }

    /**
     * Transpõe uma única nota, preservando marcadores.
     */
    protected function transposeNote(string $note): string
    {
        $note = trim($note);

        if ($note === '' || $note === '-') {
            return $note;
        }

        [$bare, $markers] = self::extractMarkers($note);

        $index = array_search($bare, self::CHROMATIC_SCALE, true);

        if ($index === false) {
            return $note; // nota não reconhecida, devolve como está
        }

        $newIndex = ($index + $this->semitones()) % 12;

        if ($newIndex < 0) {
            $newIndex += 12;
        }

        return self::CHROMATIC_SCALE[$newIndex] . $markers;
    }
}
