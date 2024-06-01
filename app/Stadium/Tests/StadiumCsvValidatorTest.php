<?php

namespace App\Stadium\Tests;

use PHPUnit\Framework\TestCase;

use App\Stadium\Validation\StadiumCsvValidator;

class StadiumCsvValidatorTest extends TestCase
{

    public function test_properly_formatted_csv_succeeds(): void
    {
        $csv = <<<CSV
        Team,FDCOUK,City,Stadium,Capacity,Latitude,Longitude,Country
        Arsenal ,Arsenal,London ,Emirates Stadium ,60361,51.555,-0.108611,England
        Aston Villa ,Aston Villa,Birmingham ,Villa Park ,42785,52.509167,-1.884722,England
        Blackburn Rovers ,Blackburn,Blackburn ,Ewood Park ,31154,53.728611,-2.489167,England
        Bolton Wanderers ,Bolton,Bolton ,Reebok Stadium ,28100,53.580556,-2.535556,England
        Chelsea ,Chelsea,London ,Stamford Bridge ,42449,51.481667,-0.191111,England
        Everton ,Everton,Liverpool ,Goodison Park ,40157,53.438889,-2.966389,England
        CSV;

        $result = StadiumCsvValidator::validate($csv);
        $this->assertTrue($result->success);
    }

    public function test_missing_headers_causes_fail(): void
    {
        // 'Latitude' is missing from this CSV
        $csv = <<<CSV
        Team,FDCOUK,City,Stadium,Capacity,Longitude,Country
        Arsenal ,Arsenal,London ,Emirates Stadium ,60361,51.555,-0.108611,England
        CSV;

        $result = StadiumCsvValidator::validate($csv);
        $this->assertFalse($result->success);
    }

    public function test_invalid_data_causes_fail(): void
    {
        // 'Latitude' and 'Longitude' should be floats, not strings
        $csv = <<<CSV
        Team,FDCOUK,City,Stadium,Capacity,Latitude,Longitude,Country
        Arsenal ,Arsenal,London ,Emirates Stadium ,60361,latitude_goes_here,longitude_goes_here,England
        CSV;

        $result = StadiumCsvValidator::validate($csv);
        $this->assertFalse($result->success);
    }
}
