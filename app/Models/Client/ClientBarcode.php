<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientBarcode extends Model
{
    use HasFactory;

    protected $table = 'client_barcodes';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];
}
