<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;

class NotificationBadge extends Component
{
    public function render()
    {
        $unreadCount = Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return view('livewire.notifications.notification-badge', [
            'unreadCount' => $unreadCount
        ]);
    }
}
