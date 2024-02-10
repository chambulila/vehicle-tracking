<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geofence extends Model
{
    use HasFactory;

    protected $table = "geofences";

    protected $fillable = [
        'latitude', 'longitude', 'vehicle_id', 
    ];
}
