<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoFile extends Model
{
    use HasFactory;

    protected $table = 'info_files';
    protected $guarded = ['id'];
    protected $hidden = ['updated_at'];
}
