<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'agent_id',
        'stage',
        'notes',
    ];

    // Relationships
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}