<?php

namespace App\Notifications;

use App\Models\EmergencyAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmergencyAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public EmergencyAlert $alert
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $severityEmoji = match ($this->alert->severity) {
            'critical' => '🚨',
            'high' => '⚠️',
            'medium' => '⚡',
            'low' => 'ℹ️',
            default => '📢',
        };

        $message = (new MailMessage)
            ->subject("{$severityEmoji} EMERGENCY: {$this->alert->title}")
            ->greeting('Emergency Alert!')
            ->line("An emergency has been reported by {$this->alert->user->name}.")
            ->line("**Type:** " . ucfirst(str_replace('_', ' ', $this->alert->type)))
            ->line("**Severity:** " . strtoupper($this->alert->severity))
            ->line("**Description:** {$this->alert->description}");

        if ($this->alert->location) {
            $message->line("**Location:** {$this->alert->location}");
        }

        return $message
            ->action('View Alert', url('/admin/emergency-alerts/' . $this->alert->id))
            ->line('Please respond immediately.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'alert_id' => $this->alert->id,
            'title' => $this->alert->title,
            'type' => $this->alert->type,
            'severity' => $this->alert->severity,
            'user_id' => $this->alert->user_id,
            'user_name' => $this->alert->user->name,
            'location' => $this->alert->location,
        ];
    }
}
