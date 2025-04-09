<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceCategory extends Model
{
    use HasFactory;

    protected $table = "price_categories";
    protected $guarded = ['id'];
    protected $hidden = ['created_at, updated_at'];
}
