<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMobile extends Model
{
    use HasFactory;
    protected $table = 'users_mobile';
    protected $fillable = [
        'mobile',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function account()
    {
        return $this->hasOne(UserAccount::class);
    }
}
