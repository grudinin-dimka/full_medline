<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Travels extends Model
{
    use HasFactory;

    protected $table = 'travels';
    protected $fillable = ['title', 'duration', 'description', 'image', 'hit', 'order', 'hide'];
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function path() {
        return Storage::url('public/travels/' . $this->image);
    }

    public function services(): HasMany
    {
        return $this->hasMany(TravelsServices::class, 'travel_id', 'id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(TravelsPrices::class, 'travel_id', 'id');
    }
}
