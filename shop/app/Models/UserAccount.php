<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;

    protected $table = 'users_account';
    protected $fillable = [
        'user_mobile_id',
        'image',
        'mojodi_account',
    ];

    public function userMobile()
    {
        return $this->belongsTo(UserMobile::class);
    }

    public function address()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(BardashtAzAccount::class);
    }

    public function deposits()
    {
        return $this->hasMany(Variz::class);
    }
}
