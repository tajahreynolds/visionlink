<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PointController extends Controller
{
    // function to get all points from database
    public function index() {
        // fetch all points from the database
        $points = Point::all();

        // return the points as json
        return response()->json($points);
    }

    // function to get information about a point with the given id
    public function info($id) {
        $point = Point::findOrFail($id);
        return response()->json($point);
    }

    // function to create a point
    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:point',
            'x' => 'required|integer',
            'y' => 'required|integer',
        ]);
        $point = Point::create($validatedData);
        return response()->json($point);
    }

    // function to update a given point by id
    public function update(Request $request, $id) {
        $point = Point::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|unique:point',
            'x' => 'required|integer',
            'y' => 'required|integer',
        ]);
        $point->update($validatedData);
        return response()->json($point);
    }

    // function to delete a given point by id
    public function destroy($id) {
        $point = Point::findOrFail($id);
        $point->delete();
        $data = array("message" => "deleted");
        return response()->json($data);
    }
}
