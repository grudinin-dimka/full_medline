<?php

namespace App\Models\Specialist;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistClinic extends Model
{
    use HasFactory;

    protected $table = 'specialist_clinics';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
