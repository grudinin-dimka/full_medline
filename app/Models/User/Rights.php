<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rights extends Model
{
    use HasFactory;

    protected $table = 'rights';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at']; 
}
