<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'timezone', 'is_platform_admin'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Defaults applied in memory so freshly created models match the schema.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'timezone' => 'Asia/Jakarta',
    ];

    public function getTimezoneAttribute($value): string
    {
        return $value ?? config('greengrid.default_timezone');
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
            'two_factor_confirmed_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Phase 1 has one active company per user: the oldest membership.
     */
    public function company(): ?Company
    {
        return $this->memberships()->with('company')->oldest()->first()?->company;
    }

    public function membershipFor(Company $company): ?Membership
    {
        return $this->memberships()->where('company_id', $company->id)->first();
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_confirmed_at !== null;
    }

    public function isPlatformAdmin(): bool
    {
        return (bool) $this->is_platform_admin;
    }
}
