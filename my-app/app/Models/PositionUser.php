<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'user_id',
        'status',
    ];

    // ارتباط با Position (یک به یک)
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // ارتباط با User (یک به یک)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
