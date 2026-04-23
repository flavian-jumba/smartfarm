<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'sender_id',
        'receiver_id',
        'parent_id',
        'subject',
        'body',
        'type',
        'priority',
        'attachment_path',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('receiver_id', $userId);
    }

    public function scopeFromUser($query, $userId)
    {
        return $query->where('sender_id', $userId);
    }

    public function scopeRootMessages($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    // Actions
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    // Helpers
    public function getIsReadAttribute(): bool
    {
        return $this->read_at !== null;
    }

    public function getIsReplyAttribute(): bool
    {
        return $this->parent_id !== null;
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'normal' => 'info',
            'low' => 'gray',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return ucfirst($this->type);
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'alert' => 'heroicon-o-exclamation-triangle',
            'report' => 'heroicon-o-document-text',
            'request' => 'heroicon-o-hand-raised',
            'broadcast' => 'heroicon-o-megaphone',
            default => 'heroicon-o-chat-bubble-left',
        };
    }
}
