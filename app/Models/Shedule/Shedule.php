<?php

namespace App\Models\Shedule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shedule extends Model
{
    use HasFactory;

    protected $table = 'shedules';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
