<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'processed_by',
        'period',
        'payment_type',
        'base_amount',
        'bonus_amount',
        'deductions',
        'net_amount',
        'currency',
        'notes',
        'payment_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'base_amount' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
            'deductions' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'payment_date' => 'date',
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

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForPeriod($query, string $period)
    {
        return $query->where('period', $period);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helpers
    public function calculateNetAmount(): float
    {
        return $this->base_amount + $this->bonus_amount - $this->deductions;
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function getFormattedNetAmountAttribute(): string
    {
        return number_format($this->net_amount, 2) . ' ' . $this->currency;
    }

    public function getPaymentTypeLabelAttribute(): string
    {
        return ucfirst($this->payment_type);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'paid' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }
}
