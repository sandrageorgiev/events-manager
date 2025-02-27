<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'app_users'; // Table name override

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'password' => 'hashed',
    ];

    public function changePassword(string $newPassword): void
    {
        $this->update(['password' => bcrypt($newPassword)]);
    }

    public function deleteUser(): void
    {
        $this->update(['enabled' => false]);
    }
}
