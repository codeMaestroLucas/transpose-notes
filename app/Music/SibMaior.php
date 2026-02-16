<?php

namespace App\Music;

class SibMaior extends MusicalScale
{
    /**
     * Instrumentos em Sib (trompete, clarinete):
     * Quando tocam "Dó", soa "Sib" (concert).
     * Para que soem a nota de concerto, transpõe-se +2 semitons.
     */
    protected function semitones(): int
    {
        return 2;
    }

    public function label(): string
    {
        return 'Trompete, Clarinete, Sax Soprano (Sib)';
    }

    public function color(): string
    {
        return '#dc3545'; // vermelho Bootstrap danger
    }
}
