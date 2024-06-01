<?php

namespace App\Stadium\Validation;

use Illuminate\Support\Str;
use App\Validation\ValidationResult;

/**
 * Validates the given contents (as a `string`) is a valid stadium CSV.
 * 
 * This class is the real workhorse of StadiumCsvRule. Given a string as input,
 * validates that it is able to be parsed and contains all required data.
 * 
 * This was separated out from StadiumCsvRule such that it is more generic/resuable,
 * makes it easier to test, and simplifies the code.
 */
class StadiumCsvValidator
{
    const HEADER_STADIUM = 'Stadium';
    const HEADER_CITY = 'City';
    const HEADER_COUNTRY = 'Country';
    const HEADER_LATITUDE = 'Latitude';
    const HEADER_LONGITUDE = 'Longitude';

    /**
     * Run the validation against a string.
     * 
     * Returns a ValidationResult containing success and/or error message
     */
    #[Pure]
    public static function validate(string $csvContents): ValidationResult
    {
        $lines = static::splitByLine($csvContents);

        if (!$lines || count($lines) <= 1) { // We expect >= 2 lines; 1 line for header, plus any content
            return new ValidationResult(
                false,
                'The :attribute file does not contain a properly-formatted CSV; requires headers plus content, separated by newline'
            );
        }

        $headerColumns = static::splitBySeparator($lines[0]);
        $stadiumIndex = array_search(static::HEADER_STADIUM, $headerColumns);
        $cityIndex = array_search(static::HEADER_CITY, $headerColumns);
        $countryIndex = array_search(static::HEADER_COUNTRY, $headerColumns);
        $latitudeIndex = array_search(static::HEADER_LATITUDE, $headerColumns);
        $longitudeIndex = array_search(static::HEADER_LONGITUDE, $headerColumns);

        // Check all required headers are present
        if (!$stadiumIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(static::HEADER_STADIUM)
            );
        }

        if (!$cityIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(static::HEADER_CITY)
            );
        }

        if (!$countryIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(static::HEADER_COUNTRY)
            );
        }

        if (!$latitudeIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(static::HEADER_LATITUDE)
            );
        }

        if (!$longitudeIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(static::HEADER_LONGITUDE)
            );
        }

        /*
         * Validate a single given line within the array and move on to the next if not end-of-file.
         * 
         * This would have been much cleaner and simpler as a simple for/while loop, but trying to keep it as functional
         * as we can, as per the requirements.
         */
        $processNextLine = function ($index, $lines) use (&$processNextLine, $stadiumIndex, $cityIndex, $countryIndex, $latitudeIndex, $longitudeIndex): ValidationResult {
            $columns = static::splitBySeparator($lines[$index]);

            // Ensure that columns follow expected data type
            if (!is_string($columns[$stadiumIndex])) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, static::HEADER_STADIUM, 'string', gettype($columns[$stadiumIndex]))
                );
            }

            if (!is_string($columns[$cityIndex])) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, static::HEADER_CITY, 'string', gettype($columns[$cityIndex]))
                );
            }

            if (!is_string($columns[$countryIndex])) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, static::HEADER_COUNTRY, 'string', gettype($columns[$countryIndex]))
                );
            }

            if (!is_numeric($columns[$longitudeIndex])) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, static::HEADER_LONGITUDE, 'float', gettype($columns[$longitudeIndex]), $columns[$longitudeIndex])
                );
            }

            if (!is_string($columns[$latitudeIndex])) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, static::HEADER_LATITUDE, 'float', gettype($columns[$latitudeIndex]), $columns[$latitudeIndex])
                );
            }


            // Handle next line or exit if EOF
            if ($index < (count($lines) - 1) && !empty($lines[$index + 1])) {
                return $processNextLine($index + 1, $lines);
            }

            return new ValidationResult(true);
        };

        // Begin recursion at line 1
        return $processNextLine(1, $lines);
    }

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

    /**
     * Helper function to format a required header error
     */
    #[Pure]
    protected static function fmtMissingRequiredHeaderError(string $headerName): string
    {
        return "The :attribute is missing the `{$headerName}` column.";
    }

    /**
     * Helper function to format a data type error
     */
    #[Pure]
    protected static function fmtDataTypeError(int $line, string $columnName, string $expectedType, string $actualType, $value = 'null'): string
    {
        return "Invalid data for column `{$columnName}` on line {$line}. Expected {$expectedType}, got {$actualType}: {$value}";
    }
}
