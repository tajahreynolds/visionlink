<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index() {
        // fetch all points from the database
        $points = Point::all();

        // return the points as json
        return response()->json($points);
    }

    public function info($id) {
        $point = Point::findOrFail($id);
        $nearest = $this->getNearest($point);
        $farthest = $this->getFarthest($point);
        $data = array("point" => $point, "nearest" => $nearest, "farthest" => $farthest);
        return response()->json($data);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'x' => 'required|integer',
            'y' => 'required|integer',
        ]);
        $point = Point::create($validatedData);
        return response()->json($point);
    }

    public function update(Request $request, $id) {
        $point = Point::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'x' => 'required|integer',
            'y' => 'required|integer',
        ]);
        $point->update($validatedData);
        return response()->json($point);
    }

    public function destroy($id) {
        $point = Point::findOrFail($id);
        $point->delete();
        $data = array("message" => "deleted");
        return response()->json($data);
    }

    private function getNearest(Point $point) {
        // todo replace data with nearest points
        $data = array($point, $point);
        return $data;
    }

    private function getFarthest(Point $point) {
        // todo replace data with farthes points
        $data = array($point, $point, $point);
        return $data;
    }
}
