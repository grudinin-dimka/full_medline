<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
   use HasFactory;

   protected $table = 'educations';
   protected $guarded = ['id'];
   protected $hidden = ['created_at', 'updated_at'];
}
