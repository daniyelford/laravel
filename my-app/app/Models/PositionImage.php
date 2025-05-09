<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'address',
        'status',
    ];

    // ارتباط با Position (یک به یک)
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
