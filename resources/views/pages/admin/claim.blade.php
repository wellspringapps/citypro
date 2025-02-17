<?php

use App\Models\Listing;
use App\Models\User;
use function Livewire\Volt\{state, rules};

state(['name', 'email', 'title', 'notes', 'pro']);

rules([
    'name' => 'required',
    'email' => 'required|email',
    'title' => 'required',
]);

$save = function () {
    $this->validate();

    $checkForListing = Listing::where('title', $this->title)->first();

    if ($checkForListing) {
        $this->addError('title', 'We found a listing that matches that name.');
        return;
    }

    $checkForUser = User::where('email', $this->email)->first();
    if ($checkForUser) {
        $this->addError('email', 'We found a user that matches that email.');
        return;
    }

    $user = User::create([
        'name' => $this->name,
        'email' => $this->email,
    ]);

    $listing = $user->listing()->create([
        'title' => $this->title,
        'pro' => $this->pro ?? false,
        'notes' => $this->notes ?? null,
    ]);

    $this->redirect(route('admin.listing', ['listing' => $listing]), navigate: true);
};

?>

<x-layouts.portal>
    @volt
        <div>
            <div class="flex justify-between">
                <div>
                    <flux:heading size="xl" level="1">Claim a listing</flux:heading>
                    <flux:subheading size="lg" class="mb-6">Add user and create listing</flux:subheading>
                </div>

                <flux:button wire:click="save" variant="primary">Save</flux:button>
            </div>

            <flux:separator variant="subtle" />

            <div class="grid grid-cols-2 gap-4">
                <flux:card class="mt-4 space-y-4">
                    <flux:heading size="lg" level="2">Create User</flux:heading>
                    <flux:input wire:model="name" label="Name" placeholder="Name" />
                    <flux:input wire:model="email" type="email" label="Email" placeholder="Email" />
                </flux:card>
                <flux:card class="mt-4 space-y-4">
                    <flux:heading size="lg" level="2">Start Listing</flux:heading>
                    <flux:input wire:model="title" label="Title" placeholder="Title" />
                    <flux:switch wire:model.live="pro" label="Enable Pro" />
                </flux:card>
            </div>
            <div>
                <flux:card class="mt-4 space-y-4">
                    <flux:editor wire:model="notes" label="Notes" />
                </flux:card>
            </div>
        </div>
    @endvolt
</x-layouts.portal>
