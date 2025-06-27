<?php

namespace App\Models\Contact;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPhone extends Model
{
    use HasFactory;

    protected $table = 'contact_phones';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at']; 
}
