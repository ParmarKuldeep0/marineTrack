<?php
// app/Models/VesselPosition.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VesselPosition extends Model
{
    use HasFactory;

    protected $table = 'vessel_positions';
    
    protected $fillable = [
        'vessel_id',
        'latitude',      // Make sure this matches your migration
        'longitude',     // Make sure this matches your migration
        'speed',
        'course',
        'heading',
        'destination',
        'eta',
        'received_at'
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'speed' => 'decimal:1',
        'course' => 'decimal:1',
        'heading' => 'decimal:1',
        'eta' => 'datetime',
        'received_at' => 'datetime'
    ];

    // Relationship back to vessel
    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }
}