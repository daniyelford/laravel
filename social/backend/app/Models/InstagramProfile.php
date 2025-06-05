<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramProfile extends Model
{
    protected $fillable = [
        'username',
        'full_name',
        'followers',
        'bio',
        'profile_pic_url',
        'error',
    ];
}
