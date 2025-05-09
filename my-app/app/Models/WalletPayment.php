<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'seller_wallet_id',
        'payment_id',
        'position_user_id',
        'position_product_order',
        'package_company_order',
        'self_wallet_action',
        'cart_id',
        'cart_action_status',
    ];

    // ارتباط با Wallet (برگشت به کیف پول)
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    // ارتباط با Payment (یک به یک)
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // ارتباط با Cart (یک به یک)
    public function cart()
    {
        return $this->belongsTo(UserCart::class, 'cart_id');
    }
}
