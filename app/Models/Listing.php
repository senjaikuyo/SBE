<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'type',
        'capacity_mw',
        'volume_available_mw',
        'price_per_kwh',
        'duration_years',
        'start_date',
        'location',
        'permit_reference',
        'photo',
        'quantity_tco2e',
        'vintage',
        'registry_standard',
        'serial_number',
        'price_per_ton',
        'status',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'capacity_mw' => 'integer',
            'volume_available_mw' => 'decimal:2',
            'price_per_kwh' => 'decimal:4',
            'duration_years' => 'integer',
            'start_date' => 'date',
            'quantity_tco2e' => 'integer',
            'vintage' => 'integer',
            'price_per_ton' => 'decimal:4',
            'version' => 'integer',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
