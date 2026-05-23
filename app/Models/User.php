<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'verification_code',
        'verification_code_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function savedProjects()
    {
        return $this->hasMany(SavedProject::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function canManageAdminArea()
    {
        return in_array($this->role, ['admin', 'super_admin'], true);
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Generate and save a verification code
     */
    public function generateVerificationCode()
    {
        $this->verification_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->verification_code_expires_at = now()->addMinutes(10);
        $this->save();
        return $this->verification_code;
    }

    /**
     * Verify if the provided code matches and is not expired
     */
    public function verifyCode($code)
    {
        return $this->verification_code === $code && 
               $this->verification_code_expires_at && 
               now()->lessThan($this->verification_code_expires_at);
    }

    /**
     * Clear the verification code after successful verification
     */
    public function clearVerificationCode()
    {
        $this->verification_code = null;
        $this->verification_code_expires_at = null;
        $this->email_verified_at = now();
        $this->save();
    }
}
