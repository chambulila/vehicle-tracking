<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_id', 'user_name', 'user_id', 'phone'
    ];
}
