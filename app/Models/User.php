<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',
        'id_number',
        'phone',
        'district',
        'category_interest',
        'suspended_until',
        'suspended_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'is_approved' => 'boolean',
            'password' => 'hashed',
        ];
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    public function isApproved(): bool
    {
        return $this->is_approved === true;
    }

    // Add this relationship
    public function orders()
    {
        return $this->hasMany(Order::class, 'vendor_id');
    }

    //suspension check
    public function isSuspended(): bool
    {
        return $this->suspended_until && $this->suspended_until->isFuture();
    }

    public function getSuspensionDuration(): ?string
    {
        if (!$this->suspended_until) return null;
        return $this->suspended_until->diffForHumans();
    }
}