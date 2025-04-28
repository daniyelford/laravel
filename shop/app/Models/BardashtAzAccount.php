<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BardashtAzAccount extends Model
{
    use HasFactory;
    protected $table = 'bardasht_az_account';
    protected $fillable = [
        'user_account_id',
        'user_cart_id',
        'meghdar',
        'time',
        'vaziate_entghal_b_hesab_karbar',
    ];

    public function userAccount()
    {
        return $this->belongsTo(UserAccount::class);
    }

    public function userCart()
    {
        return $this->belongsTo(UserCart::class);
    }
}
