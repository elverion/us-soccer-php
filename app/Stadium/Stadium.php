<?php

namespace App\Stadium;

use App\Location\Location;
use App\Weather\Weather;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    public function __construct(
        public string $name,
        public Location $location,
        public Weather $weather,
    ) {
    }
}
