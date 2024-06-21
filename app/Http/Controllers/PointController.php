<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;

class PointController extends Controller
{
    public function index() {
        // fetch all points from teh database
        $points = Point::all();

        // return the points as json
        return response()->json($points);
    }
}
