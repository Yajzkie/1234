<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserLocation; // Adjust this if you have a specific model for locations
use App\Models\Location; // Add this line
use Illuminate\Support\Facades\Auth;

class UserLocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return view('user.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $location = new Location();
        $location->name = $request->name;
        $location->description = $request->description;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
            $location->photo = $photoPath;
        }

        $location->save();

        return redirect()->route('user.index');
    }

    public function destroy($id)
    {
        $location = Location::find($id);

        if ($location) {
            $location->delete();
            return response()->json(['message' => 'Location deleted successfully.']);
        } else {
            return response()->json(['message' => 'Location not found.'], 404);
        }
    }
}
