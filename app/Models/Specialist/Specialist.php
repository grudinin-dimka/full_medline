<?php

namespace App\Models\Specialist;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Specialist extends Model
{
    use HasFactory;

    protected $table = 'specialists';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];


    public function path()
    {
        return Storage::url('public/specialists/' . $this->filename);
    }


    /* Связка с таблицей SpecialistSpecialization */
    public function specializations()
    {
        return $this->hasMany(SpecialistSpecialization::class, 'id_specialist', 'id');
    }
}
