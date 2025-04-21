<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Возможность авторизации через sanctum, выдача токенов
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';
    protected $guarded = ['id'];
    protected $hidden = ['password', 'remember_token', 'created_at', 'updated_at'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Связь с таблицей прав
    public function rights() {
        return $this->belongsTo(Rights::class, 'rightsId', 'id');
    }

    // Связь с таблицей статус
    public function status() {
        return $this->belongsTo(Status::class, 'statusId', 'id');
    }

    // Проверка на админа
    public function isAdmin() {
        return $this->rights->name === 'admin';
    }

    // Проверка на создателя
    public function isCreator() {
        return $this->rights->name === 'creator';
    }

    // Проверка на создателя
    public function isActive() {
        return $this->status->name === 'active';
    }
}
