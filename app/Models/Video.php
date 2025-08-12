<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function path()
    {
        return Storage::url('public/video/' . $this->video);
    }
}
