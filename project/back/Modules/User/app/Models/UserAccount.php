<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\User\Database\Factories\UserAccountFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_mobile_id',
        'image',
        'mojodi_account',
    ];

    public function userMobile(): BelongsTo
    {
        return $this->belongsTo(UserMobile::class, 'user_mobile_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_account_id');
    }

    public function bardashts(): HasMany
    {
        return $this->hasMany(BardashtAzAccount::class, 'user_account_id');
    }

    public function varizs(): HasMany
    {
        return $this->hasMany(Variz::class, 'user_account_id');
    }

    // protected static function newFactory(): UserAccountFactory
    // {
    //     // return UserAccountFactory::new();
    // }
}
