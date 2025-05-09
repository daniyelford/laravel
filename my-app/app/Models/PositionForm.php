<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'form_title',
        'status',
    ];

    // ارتباط با Position (یک به یک)
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // ارتباط با PositionFormQuestion (یک به چند)
    public function questions()
    {
        return $this->hasMany(PositionFormQuestion::class);
    }
}
