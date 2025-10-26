<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class UpdateProfileInformationForm extends Component
{
    public int $code = 0;
    public string $name = '';
    public string $last_name = '';
    public string $phone = '';
    public string $address = '';
    public string $document_type = '';
    public int $document_number = 0;
    public bool $is_active = false;
    public string $email = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->code = $user->code;

        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->document_type = $user->document_type;
        $this->document_number = $user->document_number;
        $this->is_active = $user->is_active;
        $this->email = $user->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->getValidated($user);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    public function render(): View
    {
        return view('livewire.profile.update-profile-information-form');
    }

    /**
     * @param User|\Illuminate\Contracts\Auth\Authenticatable|null $user
     * @return array
     */
    public function getValidated(User|\Illuminate\Contracts\Auth\Authenticatable|null $user): array
    {
        return $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'document_type' => ['required', 'string', Rule::in(['CI', 'PASSPORT'])],
            'document_number' => ['required', 'integer', 'digits_between:7,12'],
            'is_active' => ['required', 'boolean'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);
    }
}
