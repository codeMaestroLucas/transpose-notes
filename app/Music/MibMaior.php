<?php

namespace App\Music;

class MibMaior extends MusicalScale
{
    /**
     * Instrumentos em Mib (sax alto, sax barítono):
     * Quando tocam "Dó", soa "Mib" (concert).
     * Para que soem a nota de concerto, transpõe-se +9 semitons.
     */
    protected function semitones(): int
    {
        return 9;
    }

    public function label(): string
    {
        return 'Sax Alto, Clarinata Alto (Mib)';
    }

    public function color(): string
    {
        return '#198754'; // verde Bootstrap success
    }
}
