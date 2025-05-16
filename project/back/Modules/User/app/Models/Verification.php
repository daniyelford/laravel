<?php

namespace Modules\User\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\User\Database\Factories\VerificationFactory;

class Verification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'mobile',
        'code',
    ];

    // protected static function newFactory(): VerificationFactory
    // {
    //     // return VerificationFactory::new();
    // }
}
