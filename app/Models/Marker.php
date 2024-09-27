<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory;

    protected $table = 'marker';

    protected $guarded = [];

    public function connections()
    {
        return $this->belongsToMany(Marker::class, 'markerconnection', 'from_marker_id', 'to_marker_id');
    }
}
