<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    use HasFactory;

    /**
     * Defaults applied in memory so freshly created models match the schema.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'kyc_status' => 'NOT_SUBMITTED',
        'version' => 1,
    ];

    protected $fillable = [
        'name',
        'country',
        'registration_number',
        'kyc_status',
        'company_role',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
        ];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->withPivot('role', 'approval_limit')
            ->withTimestamps();
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function isApproved(): bool
    {
        return $this->kyc_status === 'APPROVED';
    }
}
