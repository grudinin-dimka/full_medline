<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPoints extends Model
{
    use HasFactory;

    protected $table = 'client_points';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
