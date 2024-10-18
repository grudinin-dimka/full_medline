<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoBlock extends Model
{
    use HasFactory;

    protected $table = 'info_blocks';
    protected $quarded = ['*'];
    protected $hidden = ['created_at', 'updated_at'];
}
