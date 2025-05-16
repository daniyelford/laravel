<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\User\Database\Factories\VarizFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Variz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
        protected $fillable = [
        'user_account_id',
        'meghdar',
        'factor_pardakht',
        'time',
    ];

    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_account_id');
    }

    // protected static function newFactory(): VarizFactory
    // {
    //     // return VarizFactory::new();
    // }
}
