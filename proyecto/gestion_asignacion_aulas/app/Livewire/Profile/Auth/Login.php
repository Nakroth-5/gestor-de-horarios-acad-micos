<?php

namespace App\Livewire\Profile\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('layouts.guest')]
class Login extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Render the component view.
     */
    public function render(): View
    {
        // Esto le dice a Livewire que use tu nuevo archivo de vista
        return view('livewire.profile.auth.login');
    }
}
