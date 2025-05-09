<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'lat',
        'lon',
        'description',
        'status',
    ];

    // ارتباط با Position (یک به یک)
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
