<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\User\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'family',
        'code_mely',
    ];
    public function mobiles(): HasMany
    {
        return $this->hasMany(UserMobile::class, 'user_id');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(UserCart::class, 'user_id');
    }
    // protected static function newFactory(): UserFactory
    // {
    //     // return UserFactory::new();
    // }
}
