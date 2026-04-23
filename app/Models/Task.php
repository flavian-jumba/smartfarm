<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'field_id',
        'assigned_to',
        'assigned_by',
        'title',
        'description',
        'type',
        'priority',
        'status',
        'due_date',
        'started_at',
        'completed_at',
        'target_latitude',
        'target_longitude',
        'completion_latitude',
        'completion_longitude',
        'gps_verified',
        'gps_tolerance_meters',
        'completion_notes',
        'completion_image_path',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'target_latitude' => 'decimal:8',
            'target_longitude' => 'decimal:8',
            'completion_latitude' => 'decimal:8',
            'completion_longitude' => 'decimal:8',
            'gps_verified' => 'boolean',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeForAgent($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    // Actions
    public function start(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete(?float $latitude = null, ?float $longitude = null, ?string $notes = null): void
    {
        $gpsVerified = false;

        if ($latitude && $longitude && $this->target_latitude && $this->target_longitude) {
            $gpsVerified = $this->verifyGpsLocation($latitude, $longitude);
        }

        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_latitude' => $latitude,
            'completion_longitude' => $longitude,
            'completion_notes' => $notes,
            'gps_verified' => $gpsVerified,
        ]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function markOverdue(): void
    {
        $this->update(['status' => 'overdue']);
    }

    // GPS Verification
    public function verifyGpsLocation(float $latitude, float $longitude): bool
    {
        if (!$this->target_latitude || !$this->target_longitude) {
            return false;
        }

        $distance = $this->calculateDistance(
            $this->target_latitude,
            $this->target_longitude,
            $latitude,
            $longitude
        );

        return $distance <= $this->gps_tolerance_meters;
    }

    /**
     * Calculate distance between two GPS coordinates in meters (Haversine formula)
     */
    protected function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    // Helpers
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date->isPast() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            'low' => 'gray',
            default => 'gray',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'gray',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            'overdue' => 'warning',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->type));
    }

    public function getTargetLocationAttribute(): ?string
    {
        if ($this->target_latitude && $this->target_longitude) {
            return "{$this->target_latitude}, {$this->target_longitude}";
        }
        return null;
    }
}
