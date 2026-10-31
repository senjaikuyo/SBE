<?php

namespace App\Support;

class StubVirusScanner implements VirusScanner
{
    public function scan(string $path): array
    {
        return [
            'clean' => true,
            'scanned_at' => now(),
        ];
    }
}
