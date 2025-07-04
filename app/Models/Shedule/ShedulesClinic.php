<?php

namespace App\Models\Shedule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShedulesClinic extends Model
{
    use HasFactory;

    protected $table = 'shedules_clinics';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
