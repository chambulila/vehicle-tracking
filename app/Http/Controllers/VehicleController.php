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
        return response()->json(['success', 'Vehicle updated']);
    }


    public function destroy($uuid)
    {
        $vehicle = \App\Models\Vehicle::where('uuid', $uuid)->first();
        if ($vehicle) {
            try {
                $vehicle->delete();
                return response()->json(['success' => 'Vehicle delete successfully']);
            } catch(\Exception $e) {
                return response()->json('error', 'An error occured');
            }
        }else {
            return response()->json('error', 'Not found!');
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

    public function sendGeocodesFromApi(Request $request)
    {
        $user = new \App\Models\Geocode;
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->vehicle_id = $request->vehicle_id;
        $user->save();

        $this->checkGeofence($user->vehicle_id);
        if ($this->status = true) {
            return response()->json(['message' => 'out of boundary'], 401);
        } else {
            return response()->json(['message' => 'within the boundary'], 200);
        }
    }

    public function checkGeofence($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        $location = Geocode::latest()->where('vehicle_id', $vehicleId)->first();
        $codes = Geofence::where('vehicle_id', $vehicleId)->first();

        // Check geofence logic (you may need to adjust the coordinates and radius)
        $allowedLatitude = $codes->tatitude;
        $allowedLongitude = $codes->longitudes;
        $allowedRadius = 10; // in kilometers

        $distance = $this->calculateDistance($location->latitude, $location->longitude, $allowedLatitude, $allowedLongitude);

        if ($distance > $allowedRadius) {
            $this->status = true;
            // Vehicle is outside the geofence
            // Trigger alert or send notification here
            // You may use Laravel's notification system
            // Example: $user->notify(new GeoFenceAlert($vehicle, $location));
            // return response()->json(['message' => 'out of boundary'], 401);

        }else {
            $this->status = false;
            // return response()->json(['message' => 'within the boundary'], 200);
        }

        // return response()->json(['message' => 'Geofence checked']);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }
    
}
