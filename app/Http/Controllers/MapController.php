<?php

namespace App\Http\Controllers;

use App\City;
use App\Helpers\Map;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function getMapData($city_id) {

        $city = City::findOrFail($city_id);

        return response()->json(Map::generateData($city));

    }
}
