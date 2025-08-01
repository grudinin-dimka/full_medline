<?php

namespace App\Models\Shedule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShedulesCurrentDay extends Model
{
    use HasFactory;

    protected $table = 'shedules_current_days';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
