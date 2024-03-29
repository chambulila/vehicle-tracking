<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $table = "vehicles";
    protected $fillable = [
        'name', 'type', 'model', 'chesis_number', 'plate_number', 'uuid', 'image', 'owner_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id')->withDefault(['name' => 'Not defined']);
    }
}
