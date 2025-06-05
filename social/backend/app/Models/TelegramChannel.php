<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramChannel extends Model
{
    protected $fillable = [
        'channel_id',
        'username',
        'title',
        'participants_count',
        'about',
    ];
}
