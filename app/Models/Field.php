<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'crop_type',
        'planting_date',
        'current_stage',
        'agent_id',
        'tenant_id',
    ];

    protected $casts = [
        'planting_date' => 'date',
    ];

    // Relationships
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(FieldUpdate::class);
    }

    // Computed Status (IMPORTANT FEATURE)
    public function getStatusAttribute()
    {
        if ($this->current_stage === 'harvested') {
            return 'completed';
        }

        $lastUpdate = $this->updates()->latest()->first();

        if (!$lastUpdate || $lastUpdate->created_at->diffInDays(now()) > 7) {
            return 'at_risk';
        }

        return 'active';
    }
}