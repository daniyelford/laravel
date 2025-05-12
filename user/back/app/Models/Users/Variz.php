<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variz extends Model
{
    use HasFactory;
    protected $table = 'variz';
    protected $fillable = [
        'user_account_id',
        'meghdar',
        'factor_pardakht',
        'time',
    ];

    public function userAccount()
    {
        return $this->belongsTo(UserAccount::class);
    }
}
