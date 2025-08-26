<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancies extends Model
{
    use HasFactory;

    protected $table = 'vacancies';
    protected $fillable = ['title', 'address', 'schedule', 'requirements', 'conditions', 'description', 'salary', 'hide'];
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
