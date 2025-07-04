<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function barcodes()
    {
        return $this->hasMany(ClientBarcode::class);
    }
}
