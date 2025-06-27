<?php

namespace App\Models\Specialist;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistEducation extends Model
{
    use HasFactory;

    protected $table = 'specialist_educations';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at']; 
}
