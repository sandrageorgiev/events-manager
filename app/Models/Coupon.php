<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $primaryKey = 'name';
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'percentage_discount',
    ];

    protected $casts = [
        'percentage_discount' => 'float',
    ];
}
