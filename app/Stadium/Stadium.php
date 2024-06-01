<?php

namespace App\Stadium;

use App\Location\Location;
use App\Weather\Weather;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    public function __construct(
        public readonly string $name,
        public readonly Location $location,
        public readonly Weather $weather,
    ) {
    }
}
