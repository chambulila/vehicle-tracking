<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geocode extends Model
{
    use HasFactory;
    protected $table = "geocodes";

    protected $fillable = [
        'latitude', 'longitude', 'vehicle_id', 
    ];
}
