<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopCart extends Model
{
    protected $table = 'shop_cart';

    protected  $primaryKey = 'cart_id';

    public $timestamps = false;
}
