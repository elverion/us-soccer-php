<?php

namespace App\Weather;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    public function __construct(
        public readonly float $temp,
        public readonly string $description,
    ) {
    }
}
