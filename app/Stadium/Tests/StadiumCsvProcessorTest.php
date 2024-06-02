<?php

namespace App\Stadium\Tests;

use App\Stadium\Data\StadiumCsvProcessor;
use PHPUnit\Framework\TestCase;

class StadiumCsvProcessorTest extends TestCase
{
    public function test_can_handle_csv_lines(): void
    {
        $csv = <<<CSV
        Team,FDCOUK,City,Stadium,Capacity,Latitude,Longitude,Country
        Arsenal ,Arsenal,London ,Emirates Stadium ,60361,51.555,-0.108611,England
        Aston Villa ,Aston Villa,Birmingham ,Villa Park ,42785,52.509167,-1.884722,England
        CSV;

        // Dumb trick, but we use this to smuggle data out of the closure
        // so that we can verify the contents match the expectations.
        $received = [];
        $count = 0;
        $handler = function ($row) use (&$received, &$count) {
            $received[$count] = $row;
            $count++;
        };

        $result = StadiumCsvProcessor::process($csv, $handler);
        $this->assertSame('Emirates Stadium', $received[0][StadiumCsvProcessor::HEADER_STADIUM]);
        $this->assertSame('London', $received[0][StadiumCsvProcessor::HEADER_CITY]);
        $this->assertSame('England', $received[0][StadiumCsvProcessor::HEADER_COUNTRY]);
        $this->assertSame('51.555', $received[0][StadiumCsvProcessor::HEADER_LATITUDE]);
        $this->assertSame('-0.108611', $received[0][StadiumCsvProcessor::HEADER_LONGITUDE]);

        $this->assertSame('Villa Park', $received[1][StadiumCsvProcessor::HEADER_STADIUM]);
        $this->assertSame('Birmingham', $received[1][StadiumCsvProcessor::HEADER_CITY]);
        $this->assertSame('England', $received[1][StadiumCsvProcessor::HEADER_COUNTRY]);
        $this->assertSame('52.509167', $received[1][StadiumCsvProcessor::HEADER_LATITUDE]);
        $this->assertSame('-1.884722', $received[1][StadiumCsvProcessor::HEADER_LONGITUDE]);
    }
}
