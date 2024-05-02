<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable =[
        'c_logo','c_name','tnc','pp','c_address','c_mobile','c_email','footer','cgst','sgst'
    ];
}
