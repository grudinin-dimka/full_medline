<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slide extends Model
{
    use HasFactory;

    protected $table = 'slides';
    protected $fillable = ['order', 'name', 'link', 'filename', 'hide'];
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function path()
    {
        return Storage::url('public/slides/' . $this->filename);
    }
}
