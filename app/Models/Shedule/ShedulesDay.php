<?php

namespace App\Models\Shedule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShedulesDay extends Model
{
    use HasFactory;

    protected $table = 'shedules_days';
    protected $guarded = ['id'];
    protected $hidden = ['created_at','updated_at'];
}
