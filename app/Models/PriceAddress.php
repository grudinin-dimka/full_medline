<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceAddress extends Model
{
    use HasFactory;

    protected $table = "price_addresses";
    protected $guarded = [];
    protected $hidden = ['created_at, updated_at'];
}
