<?php

namespace App\Stadium;

use App\Stadium\StadiumData;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $city
 * @property string $country
 * @property float $lat
 * @property float $long
 */
class Stadium extends Model
{
    protected $dataClass = StadiumData::class;
    protected $table = 'stadiums';
}
