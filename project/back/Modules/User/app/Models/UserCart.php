<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\User\Database\Factories\UserCartFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
        protected $fillable = [
        'user_id',
        'shoamre_shaba',
        'shomare_cart',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bardashts(): HasMany
    {
        return $this->hasMany(BardashtAzAccount::class, 'user_cart_id');
    }

    // protected static function newFactory(): UserCartFactory
    // {
    //     // return UserCartFactory::new();
    // }
}
