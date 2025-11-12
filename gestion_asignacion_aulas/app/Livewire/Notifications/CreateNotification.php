<?php

namespace App\Livewire\Notifications;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CreateNotification extends Component
{
    public $user_id = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    public $priority = 'info';

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'subject' => 'required|string|max:255',
        'message' => 'required|string|min:10',
        'priority' => 'required|in:info,important,urgent',
    ];

    protected $messages = [
        'user_id.required' => 'Debe seleccionar un destinatario',
        'user_id.exists' => 'El usuario seleccionado no existe',
        'subject.required' => 'El asunto es obligatorio',
        'subject.max' => 'El asunto no puede exceder 255 caracteres',
        'message.required' => 'El mensaje es obligatorio',
        'message.min' => 'El mensaje debe tener al menos 10 caracteres',
        'priority.required' => 'Debe seleccionar una prioridad',
        'priority.in' => 'La prioridad seleccionada no es válida',
    ];

    public function updated($property, $value)
    {
        if ($property === 'user_id' && $value) {
            $user = User::find($value);
            $this->email = $user ? $user->email : '';
        } elseif ($property === 'user_id' && !$value) {
            $this->email = '';
        }
    }

    public function render(): View
    {
        $users = User::orderBy('name')->get();

        return view('livewire.notifications.create-notification', [
            'users' => $users,
        ]);
    }

    public function sendNotification()
    {
        $this->validate();

        $recipient = User::findOrFail($this->user_id);
        $sender = auth()->user();

        $service = new NotificationService();
        $service->createDirectMessage(
            $recipient,
            $sender,
            $this->subject,
            $this->message
        );

        session()->flash('success', 'Notificación enviada exitosamente');

        return redirect()->route('notifications.create');
    }

    public function cancel()
    {
        return redirect()->route('notifications.create');
    }
}
