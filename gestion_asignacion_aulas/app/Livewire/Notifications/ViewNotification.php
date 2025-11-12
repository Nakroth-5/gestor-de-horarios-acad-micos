<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewNotification extends Component
{
    public $notification;
    public $notificationId;

    public function mount($id)
    {
        $this->notificationId = $id;
        $this->notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Marcar como leída si no lo está
        if (!$this->notification->read_at) {
            $this->notification->markAsRead();
        }
    }

    public function deleteNotification()
    {
        $this->notification->delete();
        session()->flash('success', 'Notificación eliminada exitosamente');
        return redirect()->route('notifications.index');
    }

    public function render(): View
    {
        return view('livewire.notifications.view-notification');
    }
}
