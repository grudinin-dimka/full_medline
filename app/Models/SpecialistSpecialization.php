<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistSpecialization extends Model
{
   use HasFactory;

   protected $table = 'specialist_specializations';
   protected $guarded = ['id'];
   protected $hidden = ['created_at', 'updated_at'];
}
