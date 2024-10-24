<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactClinic extends Model
{
    use HasFactory;

    protected $table = 'contact_clinics';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at']; 
}
