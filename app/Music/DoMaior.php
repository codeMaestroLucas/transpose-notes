<?php

namespace App\Music;

class DoMaior extends MusicalScale
{
    protected function semitones(): int
    {
        return 0; // Tom de concerto — sem transposição
    }

    public function label(): string
    {
        return 'Flauta, Escaleta (Dó)';
    }

    public function color(): string
    {
        return '#0d6efd'; // azul Bootstrap primary
    }
}
