<?php

namespace App\Models\Prices;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceFile extends Model
{
    use HasFactory;

    protected $table = "price_files";
    protected $guarded = ['id'];
    protected $hidden = ['updated_at'];
}
