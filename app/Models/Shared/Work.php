<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
   use HasFactory;

   protected $table = 'works';
   protected $guarded = ['id'];
   protected $hidden = ['created_at', 'updated_at'];
}
