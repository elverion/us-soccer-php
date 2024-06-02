<?php

namespace App\Stadium\Validation;

use App\Validation\ValidationResult;
use App\System\Traits\ParsesCsv;
use App\Stadium\Data\StadiumCsvProcessor;

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
    use ParsesCsv;

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
                ':attribute does not contain a properly-formatted CSV; requires headers plus content, separated by newline'
            );
        }

        $headerColumns = static::splitBySeparator($lines[0]);
        $stadiumIndex = array_search(StadiumCsvProcessor::HEADER_STADIUM, $headerColumns);
        $cityIndex = array_search(StadiumCsvProcessor::HEADER_CITY, $headerColumns);
        $countryIndex = array_search(StadiumCsvProcessor::HEADER_COUNTRY, $headerColumns);
        $latitudeIndex = array_search(StadiumCsvProcessor::HEADER_LATITUDE, $headerColumns);
        $longitudeIndex = array_search(StadiumCsvProcessor::HEADER_LONGITUDE, $headerColumns);

        // Check all required headers are present
        if (!$stadiumIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(StadiumCsvProcessor::HEADER_STADIUM)
            );
        }

        if (!$cityIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(StadiumCsvProcessor::HEADER_CITY)
            );
        }

        if (!$countryIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(StadiumCsvProcessor::HEADER_COUNTRY)
            );
        }

        if (!$latitudeIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(StadiumCsvProcessor::HEADER_LATITUDE)
            );
        }

        if (!$longitudeIndex) {
            return new ValidationResult(
                false,
                static::fmtMissingRequiredHeaderError(StadiumCsvProcessor::HEADER_LONGITUDE)
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
            $stadiumName = $columns[$stadiumIndex] ?? null;
            $stadiumCity = $columns[$cityIndex] ?? null;
            $stadiumCountry = $columns[$countryIndex] ?? null;
            $stadiumLat = $columns[$latitudeIndex] ?? null;
            $stadiumLong = $columns[$longitudeIndex] ?? null;

            // Ensure that columns follow expected data type
            if (!is_string($stadiumName)) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, StadiumCsvProcessor::HEADER_STADIUM, 'string', gettype($stadiumName), $stadiumName)
                );
            }

            if (!is_string($stadiumCity)) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, StadiumCsvProcessor::HEADER_CITY, 'string', gettype($stadiumCity), $stadiumCity)
                );
            }

            if (!is_string($stadiumCountry)) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, StadiumCsvProcessor::HEADER_COUNTRY, 'string', gettype($stadiumCountry), $stadiumCountry)
                );
            }

            if (!is_numeric($stadiumLat)) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, StadiumCsvProcessor::HEADER_LATITUDE, 'float', gettype($stadiumLat), $stadiumLat)
                );
            }

            if (!is_numeric($stadiumLong)) {
                return new ValidationResult(
                    false,
                    static::fmtDataTypeError($index + 1, StadiumCsvProcessor::HEADER_LONGITUDE, 'float', gettype($stadiumLong), $stadiumLong)
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
     * Helper function to format a required header error
     */
    #[Pure]
    protected static function fmtMissingRequiredHeaderError(string $headerName): string
    {
        return ":attribute is missing the `{$headerName}` column.";
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
