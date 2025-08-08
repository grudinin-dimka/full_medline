<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travels extends Model
{
    use HasFactory;

    protected $table = 'travels';
    protected $fillable = ['title', 'duration', 'food', 'description', 'filename'];
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
