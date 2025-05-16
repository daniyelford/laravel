<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\User\Database\Factories\BardashtAzAccountFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BardashtAzAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_account_id',
        'user_cart_id',
        'meghdar',
        'time',
        'vaziate_entghal_b_hesab_karbar',
    ];

    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_account_id');
    }

    public function userCart(): BelongsTo
    {
        return $this->belongsTo(UserCart::class, 'user_cart_id');
    }

    // protected static function newFactory(): BardashtAzAccountFactory
    // {
    //     // return BardashtAzAccountFactory::new();
    // }
}
