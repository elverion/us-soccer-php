<?php

namespace App\Stadium\Data;

use App\Stadium\StadiumData;
use Closure;
use App\System\Traits\ParsesCsv;

class StadiumCsvProcessor
{
    use ParsesCsv;

    const HEADER_STADIUM = 'Stadium';
    const HEADER_CITY = 'City';
    const HEADER_COUNTRY = 'Country';
    const HEADER_LATITUDE = 'Latitude';
    const HEADER_LONGITUDE = 'Longitude';

    /**
     * Begin processing of a stadium CSV file.
     * 
     * This assumes the file has already been validated!
     * Each data line within the file will be passed to the given `$lineHandler` closure.
     * Your closure should expect to receive a `StadiumData` representing that lines' data.
     */
    #[Pure]
    public static function process(string $csvContents, Closure $lineHandler)
    {
        $lines = static::splitByLine($csvContents);

        $headerColumns = static::splitBySeparator($lines[0]);
        $stadiumIndex = array_search(static::HEADER_STADIUM, $headerColumns);
        $cityIndex = array_search(static::HEADER_CITY, $headerColumns);
        $countryIndex = array_search(static::HEADER_COUNTRY, $headerColumns);
        $latitudeIndex = array_search(static::HEADER_LATITUDE, $headerColumns);
        $longitudeIndex = array_search(static::HEADER_LONGITUDE, $headerColumns);

        /*
         * Process a single data line by passing the columns to the `$lineHandler` closure given by the user.
         * The data will be passed in a mapped fashion.
         */
        $processNextLine = function ($index, $lines) use (&$processNextLine, $lineHandler, $stadiumIndex, $cityIndex, $countryIndex, $latitudeIndex, $longitudeIndex) {
            $columns = static::splitBySeparator($lines[$index]);
            $stadiumData = new StadiumData(
                name: $columns[$stadiumIndex],
                city: $columns[$cityIndex],
                country: $columns[$countryIndex],
                lat: $columns[$latitudeIndex],
                long: $columns[$longitudeIndex],
            );

            $lineHandler($stadiumData);

            // Handle next line or exit if EOF
            if ($index < (count($lines) - 1) && !empty($lines[$index + 1])) {
                return $processNextLine($index + 1, $lines);
            }
        };

        // Begin recursion at line 1
        return $processNextLine(1, $lines);
    }
}
