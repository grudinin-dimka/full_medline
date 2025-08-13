<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelsPrices extends Model
{
    use HasFactory;

    protected $table = 'travels_prices';
    protected $fillable = ['type', 'subtype', 'price', 'travel_id'];
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
