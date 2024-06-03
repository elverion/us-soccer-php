<?php

namespace App\Stadium\Tests;

use App\Stadium\Data\StadiumCsvProcessor;
use PHPUnit\Framework\TestCase;
use App\Stadium\StadiumData;

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
        $handler = function (StadiumData $data) use (&$received, &$count) {
            $received[$count] = $data;
            $count++;
        };

        $result = StadiumCsvProcessor::process($csv, $handler);
        $this->assertSame('Emirates Stadium', $received[0]->name);
        $this->assertSame('London', $received[0]->city);
        $this->assertSame('England', $received[0]->country);
        $this->assertSame(51.555, $received[0]->lat);
        $this->assertSame(-0.108611, $received[0]->long);

        $this->assertSame('Villa Park', $received[1]->name);
        $this->assertSame('Birmingham', $received[1]->city);
        $this->assertSame('England', $received[1]->country);
        $this->assertSame(52.509167, $received[1]->lat);
        $this->assertSame(-1.884722, $received[1]->long);
    }
}
