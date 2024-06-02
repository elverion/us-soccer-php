<?php

namespace App\System\Traits;

use Illuminate\Support\Str;

use App\Validation\ValidationResult;

trait ParsesCsv
{
    /**
     * Explodes a string by line. Accepts either "\r\n" or "\n" as lines.
     * Returns false if no lines could be split.
     */
    #[Pure]
    protected static function splitByLine(string $contents): false|array
    {
        return preg_split('/\n|\r\n/', $contents);
    }

    /**
     * Like explode()-ing by a separator, but also handles trimming whitespace on each item
     */
    #[Pure]
    protected static function splitBySeparator(string $line, string $seperator = ','): array
    {
        return Str::of($line)
            ->explode($seperator)
            ->map(fn ($item) => trim($item))
            ->toArray();
    }
}
