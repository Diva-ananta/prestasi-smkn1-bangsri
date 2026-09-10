<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_admin', 'sipintu_id', 'sipintu_data'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin && $this->role === 'master_admin';
    }

    public static function isConfiguredAdminEmail(?string $email): bool
    {
        return filled($email)
            && static::query()
                ->where('email', $email)
                ->where('is_admin', true)
                ->where('role', 'master_admin')
                ->exists();
    }
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'sipintu_data' => 'array',
        ];
    }
}
