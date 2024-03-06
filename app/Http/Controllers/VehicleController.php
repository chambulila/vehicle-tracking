<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Geofence;
use App\Models\Geocode;
use App\Models\User;
use App\Models\SmsHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;
use DB;
use Twilio\Rest\Client;
use Auth;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;


class VehicleController extends Controller
{

    public $status = null;
    public function __construct()
    {
        // return $this->middleware('auth');
    }

    public function index()
    {
        // dd(User::whereRoleid(0)->get(['id', 'fname']));
        // dd(Vehicle::where('owner_id', Auth::User()->id)->get());
        return view('pages.vehicle.index', [
            'vehicles' => Auth::User()->roleId === 1 ? Vehicle::all() : Vehicle::where('owner_id', Auth::User()->id)->get(),
            'owners' => User::whereRoleid(0)->get(['id', 'fname'])
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
        // request()->validate([
        //     'name' => 'required',
        //     'model' => 'required',
        //     'type' => 'required',
        //     'plate_number' => 'required',
        //     'chesis_number' => 'required',
        // ]);
        // if (request()->hasFile('image')) {
        //     $path = time().request('image').extension();
        //     request('image').move(public_path('images'), $path);

        //     \App\Models\Vehicle::create([
        //         'name' => request('name'),
        //         'model' => request('model'),
        //         'type' => request('type'),
        //         'plate_number' => request('plate_number'),
        //         'chesis_number' => request('chesis_number'),
        //         'image' => $path,
        //         'uuid' => \Str::uuid(),
        //     ]);
        // } else {
            \App\Models\Vehicle::create([
                'name' => request('name'),
                'model' => request('model'),
                'type' => request('type'),
                'plate_number' => request('plate_number'),
                'chesis_number' => request('chesis_number'),
                'owner_id' => request('owner'),
                'uuid' => \Str::uuid(),
            ]);
        // }
 
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
        $vehicle_id = 9;
        $user = User::whereIn('id', Vehicle::whereId($vehicle_id)->get(['owner_id']))->first();
        // dd($user->phone);
        $parameters = [
            "phone" => $user->phone,
            "plate_number" => Vehicle::whereId($vehicle_id)->get(['plate_number']),
            "user_id" => $user->id,
            "user_name" => $user->fname,
            "email" => 'kelvinchambulila5@gmail.com',
            "url" => 'http://127.0.0.1:8000/map',
        ];

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

    $sms = SmsHistory::where('vehicle_id', $vehicle_id)->where('created_at', now()->toDateString())->get()->count();
    if ($sms < 3 & $distance > $threshold) {
        SmsHistory::create($parameters);
        $this->sendEmail($parameters);
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

    public function sendSms()
    {
        $TWILIO_SID='AC7138f025430ea3d244470aa4000ac0f5';
        $TWILIO_TOKEN='55dc6f6186094c33e6a286dc03faf533';
        $TWILIO_PHONE= +14088053716;
        // $account_sid = getenv('TWILIO_SID');
        // $auth_token = getenv('TWILIO_TOKEN');
        // $twilio_number = getenv('TWILIO_PHONE');

        $account_sid = $TWILIO_SID;
        $auth_token = $TWILIO_TOKEN;
        $twilio_number = $TWILIO_PHONE;
        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            '+255744320059',
            array(
                'from' => $twilio_number,
                'body' => 'Habri! Gari lako lipo nje ya mipaka uliyoiweka'
            )
        );
        return true;
    }

    public function sendEmail($parameters)
    {
        $title = 'How are you doing!';
        $body = 'Your vehicle with plate number ' .$parameters["plate_number"]. 'is out of your pre defined area. Click the link to track ' .$parameters["url"];

        Mail::to($parameters["email"])->send(new SendMail($title, $body));

        return "Email sent successfully!";
    }
    
}
