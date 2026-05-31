<?php
// app/Models/Vessel.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vessel extends Model
{
    use HasFactory;

    protected $fillable = [
        'mmsi',
        'name',
        'type',
        'flag',
        'Length',
        'Width'
    ];

    protected $casts = [
        'Length' => 'decimal:2',
        'Width' => 'decimal:2',
    ];

    // Relationship to positions
    public function positions()
    {
        return $this->hasMany(VesselPosition::class)->orderBy('received_at', 'desc');
    }

    // Add this missing relationship for latest position
    public function latestPosition()
    {
        return $this->hasOne(VesselPosition::class)->latest('received_at');
    }
}