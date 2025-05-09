<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function chats()
    {
        return $this->hasMany(ProductChat::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function videos()
    {
        return $this->hasMany(ProductVideo::class);
    }

}
