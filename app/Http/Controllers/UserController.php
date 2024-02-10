<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index()
    {
        return view('pages.user.index', [
            'users' => User::all()
        ]);
    }



    public function callData()
    {
        $users = User::all();
        if(request('keyword') != ''){
        $users = User::where('name','LIKE','%'.request('keyword').'%')->get();
        }
        return response()->json([
           'users' => $users
        ]);
    }
    public function create()
    {
        return view('pages.user.create');
    }


    public function store(Request $request)
    {
        \App\Models\User::create([
            'fname' => request('fname'),
            'lname' => request('lname'),
            'location' => request('location'),
            'phone' =>  request('phone'),
            'password' => request('password') ? \Hash::make(request('password'),'1234567890') : \Hash::make(request('phone')),
            'email' =>  request('email'),
            'roleId' => request('roleId')
        ]);
    }


    public function show($user)
    {
        $user = User::where('id', $user)->first();
        return response()->json($user);
    }


    public function edit($user)
    {
        $user = User::where('id', $user)->first();
        return response()->json($user);
    }


    public function update($user)
    {
        // dd( User::where('id', $user)->first());
     try {
        User::where('id', $user)->first()->update([
            'fname' => request('fname'),
            'lname' => request('lname'),
            'location' => request('location'),
            'phone' =>  request('phone'),
            'password' => request('password') ? \Hash::make(request('password')) : \Hash::make(request('phone')),
            'email' =>  request('email'),
            'roleId' => (int)request('roleId')
        ]);
        return response()->json('success', 'User info updated successfully');
     } catch (\Exception $th) {
        return response()->json('error', $th);
     }
    }


    public function destroy(string $id)
    {
        $vehicle = User::where('id', $id)->first();
        if ($vehicle) {
            try {
                $vehicle->delete();
                return response()->json(['success' => 'User deleted successfully']);
            } catch(\Exception $e) {
                return response()->json('error', 'An error occured');
            }
        }else {
            return response()->json('error', 'Not found!');
        }
    }
}
