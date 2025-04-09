<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistCertificate extends Model
{
    use HasFactory;

    protected $table = 'specialist_certificates';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
