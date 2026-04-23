<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'task_id',
        'field_id',
        'log_date',
        'check_in_time',
        'check_out_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'activities_performed',
        'notes',
        'weather_conditions',
        'hours_worked',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'check_in_time' => 'datetime:H:i',
            'check_out_time' => 'datetime:H:i',
            'check_in_latitude' => 'decimal:8',
            'check_in_longitude' => 'decimal:8',
            'check_out_latitude' => 'decimal:8',
            'check_out_longitude' => 'decimal:8',
            'hours_worked' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('log_date', $date);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('log_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('log_date', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ]);
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'checked_out');
    }

    // Actions
    public function checkIn(?float $latitude = null, ?float $longitude = null): void
    {
        $this->update([
            'check_in_time' => now()->format('H:i'),
            'check_in_latitude' => $latitude,
            'check_in_longitude' => $longitude,
            'status' => 'checked_in',
        ]);
    }

    public function checkOut(?float $latitude = null, ?float $longitude = null): void
    {
        $checkOutTime = now();
        $hoursWorked = null;

        if ($this->check_in_time) {
            $checkInTime = $this->log_date->setTimeFromTimeString($this->check_in_time->format('H:i:s'));
            $hoursWorked = round($checkInTime->diffInMinutes($checkOutTime) / 60, 2);
        }

        $this->update([
            'check_out_time' => $checkOutTime->format('H:i'),
            'check_out_latitude' => $latitude,
            'check_out_longitude' => $longitude,
            'hours_worked' => $hoursWorked,
            'status' => 'checked_out',
        ]);
    }

    public function approve(User $approver): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    // Helpers
    public function getCheckInLocationAttribute(): ?string
    {
        if ($this->check_in_latitude && $this->check_in_longitude) {
            return "{$this->check_in_latitude}, {$this->check_in_longitude}";
        }
        return null;
    }

    public function getCheckOutLocationAttribute(): ?string
    {
        if ($this->check_out_latitude && $this->check_out_longitude) {
            return "{$this->check_out_latitude}, {$this->check_out_longitude}";
        }
        return null;
    }

    public function getFormattedHoursAttribute(): string
    {
        if ($this->hours_worked) {
            $hours = floor($this->hours_worked);
            $minutes = round(($this->hours_worked - $hours) * 60);
            return sprintf('%dh %02dm', $hours, $minutes);
        }
        return '-';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'checked_in' => 'info',
            'checked_out' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'gray',
        };
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'checked_in';
    }
}
