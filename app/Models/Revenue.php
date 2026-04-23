<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'field_id',
        'recorded_by',
        'title',
        'description',
        'source',
        'amount',
        'currency',
        'quantity',
        'unit',
        'unit_price',
        'revenue_date',
        'buyer_name',
        'buyer_contact',
        'receipt_path',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'revenue_date' => 'date',
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

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('revenue_date', [$startDate, $endDate]);
    }

    public function scopeForField($query, $fieldId)
    {
        return $query->where('field_id', $fieldId);
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    // Helpers
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function getQuantityDisplayAttribute(): ?string
    {
        if ($this->quantity && $this->unit) {
            return number_format($this->quantity, 2) . ' ' . $this->unit;
        }
        return null;
    }

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'harvest_sale' => 'Harvest Sale',
            'livestock_sale' => 'Livestock Sale',
            'equipment_rental' => 'Equipment Rental',
            'subsidy' => 'Government Subsidy',
            'other' => 'Other',
            default => ucfirst($this->source),
        };
    }
}
