<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Geofence;
use App\Models\Geocode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use DB;

class VehicleController extends Controller
{

    public $status = null;
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.vehicle.index', [
            'vehicles' => Vehicle::all()
        ]);
    }



    public function callData()
    {
        $vehicles = Vehicle::all();
        if(request('keyword') != ''){
        $vehicles = Vehicle::where('name','LIKE','%'.request('keyword').'%')->get();
        }
        return response()->json([
           'vehicles' => $vehicles
        ]);
    }
    public function create()
    {
        return view('pages.vehicle.create');
    }


    public function store(Request $request)
    {
        // dd(request()->all());
        request()->validate([
            'name' => 'required',
            'model' => 'required',
            'type' => 'required',
            'plate_number' => 'required',
            'chesis_number' => 'required',
        ]);
        if (request()->hasFile('image')) {
            $path = time().request('image').extension();
            request('image').move(public_path('images'), $path);

            \App\Models\Vehicle::create([
                'name' => request('name'),
                'model' => request('model'),
                'type' => request('type'),
                'plate_number' => request('plate_number'),
                'chesis_number' => request('chesis_number'),
                'image' => $path,
                'uuid' => \Str::uuid(),
            ]);
        } else {
            \App\Models\Vehicle::create([
                'name' => request('name'),
                'model' => request('model'),
                'type' => request('type'),
                'plate_number' => request('plate_number'),
                'chesis_number' => request('chesis_number'),
                'uuid' => \Str::uuid(),
            ]);
        }
 
        return response()->json(['success' => 'Vehicle added']);
    }


    public function show(Vehicle $vehicle)
    {
        $vehicle = Vehicle::where('uuid', $vehicle)->first();
        return response()->json($vehicle);
    }


    public function edit($vehicle)
    {
        $vehicle = Vehicle::where('uuid', $vehicle)->first();
        return response()->json($vehicle);
    }


    public function update($uuid)
    {
        // dd(request()->all());
        request()->validate([
            'name' => 'required',
            'model' => 'required',
            'type' => 'required',
            'plate_number' => 'required',
            'chesis_number' => 'required',
        ]);
        if (request()->hasFile('image')) {
            $path = time().request('image').extension();
            request('image').move(public_path('images'), $path);

            \App\Models\Vehicle::where('uuid', $uuid)->update([
                'name' => request('name'),
                'model' => request('model'),
                'type' => request('type'),
                'plate_number' => request('plate_number'),
                'chesis_number' => request('chesis_number'),
                'image' => $path,
            ]);
        } else {
            \App\Models\Vehicle::where('uuid', $uuid)->update([
                'name' => request('name'),
                'model' => request('model'),
                'type' => request('type'),
                'plate_number' => request('plate_number'),
                'chesis_number' => request('chesis_number'),
            ]);
        }
        return response()->json(['success' => 'Vehicle updated']);
    }


    public function destroy($uuid)
    {
        $vehicle = \App\Models\Vehicle::where('uuid', $uuid)->first();
        if ($vehicle) {
            try {
                $vehicle->delete();
                return response()->json(['success' => 'Vehicle delete successfully']);
            } catch(\Exception $e) {
                return response()->json(['error' => 'An error occured']);
            }
        }else {
            return response()->json(['error' => 'Not found!']);
        }
    }

    public function map()
    {
        return view('pages.vehicle.map');
    }

    public function showGeofenceForm()
    {
        return view('pages.vehicle.geofenceForm');
    }

    public function saveGeofence(Request $request)
    {
        $user = new \App\Models\Geofence;
        $user->latitude = $request->input('latitude');
        $user->longitude = $request->input('longitude');
        $user->vehicle_id = 1;
        $user->save();

        return redirect('/')->with('success', 'Geofence saved successfully.');
    }

    public function checkBoundary()
    {
        $vehicle = Geocode::findOrFail(1);
        $vehicleId = $vehicle->id;

        // Coordinates of the predefined area
        $predefinedLatitude = Geofence::latest()->where('vehicle_id', $vehicleId)->first()->latitude;
        $predefinedLongitude = Geofence::latest()->where('vehicle_id', $vehicleId)->first()->longitude;
    
        // Calculate the distance using the Haversine formula
        $distance = $this->haversineDistance(
            $predefinedLatitude,
            $predefinedLongitude,
            $vehicle->latitude,
            $vehicle->longitude
        );
        $threshold = 10000;
    // Check if the distance is greater than the threshold
    if ($distance > $threshold) {
        return response()->json(['message' => 'out of boundary']);
    }

    return response()->json(['message' => 'within the predefined area']);
    }

    // Haversine formula function
function haversineDistance($lat1, $lon1, $lat2, $lon2)
{
    // Radius of the Earth in kilometers
    $R = 6371;

    // Convert latitude and longitude from degrees to radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Calculate the differences
    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;

    // Haversine formula
    $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Calculate the distance
    $distance = $R * $c;
    return $distance;
}

    public function sendGeocodesFromApi(Request $request)
    {
        $user = new \App\Models\Geocode;
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->vehicle_id = $request->vehicle_id;
        $user->save();

        // $this->checkGeofence($user->vehicle_id);
    }

    
}
