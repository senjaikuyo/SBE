<?php

namespace App\Support;

interface VirusScanner
{
    /**
     * Run content-based virus scanning. Phase 1 stub always passes.
     */
    public function scan(string $path): array;
}
