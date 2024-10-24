<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;
 
    protected $table = 'mails';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at']; 
}
