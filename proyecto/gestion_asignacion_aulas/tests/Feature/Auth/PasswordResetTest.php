<?php

namespace Tests\Feature\Auth;

use App\Livewire\Profile\Auth\ForgotPassword;
use App\Livewire\Profile\Auth\ResetPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    // Este ya estaba bien
    $response
        ->assertSeeLivewire(ForgotPassword::class)
        ->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    // CAMBIO: Volt::test -> Livewire::test
    Livewire::test(ForgotPassword::class)
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    // CAMBIO: Volt::test -> Livewire::test
    Livewire::test(ForgotPassword::class)
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        // CAMBIO: assertSeeVolt -> assertSeeLivewire
        $response
            ->assertSeeLivewire(ResetPassword::class)
            ->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    // CAMBIO: Volt::test -> Livewire::test
    Livewire::test(ForgotPassword::class)
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
        // CAMBIO: Volt::test -> Livewire::test
        $component = Livewire::test(ResetPassword::class, ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'password')
            ->set('password_confirmation', 'password');

        $component->call('resetPassword');

        $component
            ->assertRedirect('/login')
            ->assertHasNoErrors();

        return true;
    });
});
