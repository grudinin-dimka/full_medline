<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMail extends Model
{
    use HasFactory;

    protected $table = 'contact_mails';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at']; 
}
