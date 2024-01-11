<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use DB;

class VehicleController extends Controller
{

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
}
