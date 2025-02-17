<?php

use App\Livewire\Forms\LoginForm;

use function Laravel\Folio\{name};
use function Livewire\Volt\{form};

name('login');

form(LoginForm::class);

$save = function () {
    $this->validate();
    $this->form->submit();
};

?>

<x-layouts.public>
    <x-slot name="title">Login</x-slot>

    @volt
        <div class="">
            <div class="absolute inset-0 top-1/2 mx-auto max-w-2xl -translate-y-1/2 transform p-8">
                <form wire:submit.prevent="save" class="w-full space-y-4">
                    <flux:heading size="xl">Login</flux:heading>
                    <flux:subheading>
                        Enter your email and password to login. We will email you a magic link to login.
                    </flux:subheading>
                    <flux:input type="email" wire:model="form.email" label="Email" />
                    <div class="flex justify-end">
                        <flux:button variant="primary" type="submit">Login</flux:button>
                    </div>
                </form>
            </div>
        </div>
    @endvolt
</x-layouts.public>
