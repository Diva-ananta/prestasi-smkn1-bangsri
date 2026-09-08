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
        $adminEmail = config('auth.admin_email');

        return $this->is_admin
            && filled($adminEmail)
            && strcasecmp((string) $this->email, (string) $adminEmail) === 0;
    }

    public static function isConfiguredAdminEmail(?string $email): bool
    {
        $adminEmail = config('auth.admin_email');

        return filled($email)
            && filled($adminEmail)
            && strcasecmp($email, $adminEmail) === 0;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'sipintu_data' => 'array',
        ];
    }
}
