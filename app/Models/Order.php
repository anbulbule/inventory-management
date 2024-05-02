<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'order_id','shop_id','products_id','price','qty','unit','total_price','status','remarks'
    ];
}
