<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $fillable = [
        'name',
        'family',
        'code_mely',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function mobiles()
    {
        return $this->hasMany(UserMobile::class);
    }

    public function account()
    {
        return $this->hasOne(UserAccount::class, 'user_mobile_id');
    }

    public function address()
    {
        return $this->hasOneThrough(UserAddress::class, UserAccount::class);
    }

    public function carts()
    {
        return $this->hasMany(UserCart::class);
    }

    public function withdrawals()
    {
        return $this->hasManyThrough(BardashtAzAccount::class, UserAccount::class);
    }

    public function deposits()
    {
        return $this->hasManyThrough(Variz::class, UserAccount::class);
    }
}
