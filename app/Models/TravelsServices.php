<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelsServices extends Model
{
    use HasFactory;

    protected $table = 'travels_services';
    protected $fillable = ['title', 'description', 'travel_id'];
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
