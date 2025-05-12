<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = 'users_address';
    protected $fillable = [
        'user_account_id',
        'address',
        'code_posty',
        'lat',
        'long',
    ];

    public function userAccount()
    {
        return $this->belongsTo(UserAccount::class);
    }
}
