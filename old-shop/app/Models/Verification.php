<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Verification extends Model
{
    use HasFactory;
    protected $table = 'verifications';
    protected $fillable = [
        'mobile',
        'code',
    ];

    /**
     * بررسی این که آیا این کد هنوز معتبر است یا نه
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->created_at->gt(now()->subMinutes(2));
    }

    /**
     * دامنه برای پیدا کردن کد معتبر
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $mobile
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValidCode($query, $mobile, $code)
    {
        return $query->where('mobile', $mobile)->where('code', $code)->where('created_at', '>=', now()->subMinutes(2));
    }
}
