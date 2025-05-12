<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    use HasFactory;
    protected $table = 'users_cart';
    protected $fillable = [
        'user_id',
        'shoamre_shaba',
        'shomare_cart',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
