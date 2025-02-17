<?php

use App\Models\User;
use function Livewire\Volt\{state, mount};

state(['name', 'email', 'listing']);

$save = function () {
    $user = User::create([
        'name' => $this->name,
        'email' => $this->email,
    ]);

    $user->listing()->save($this->listing);

    Flux::toast('User created.');
    Flux::modals()->close();

    $this->dispatch('listing-update');
};

?>

<flux:modal name="add-user-{{ $listing->public_id }}">
    <div class="min-w-96">
        <div class="mb-6">
            <flux:heading size="lg">Add user to {{ $listing->title }}</flux:heading>
        </div>
        <flux:separator variant="subtle" />
        <div class="my-6 space-y-4">
            <flux:input wire:model="name" label="Name" />
            <flux:input wire:model="email" type="email" label="Email" />
        </div>
        <flux:separator variant="subtle" />
        <div class="mt-6 flex justify-end">
            <flux:button wire:click="save" variant="primary">Save</flux:button>
        </div>
    </div>
</flux:modal>
