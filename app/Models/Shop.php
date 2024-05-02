<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id','shop_name','mobile','email','password','shop_address','city',
        'pincode','documents','status','remarks','token'
    ];
}
