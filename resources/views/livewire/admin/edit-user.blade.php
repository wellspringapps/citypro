<?php

use function Livewire\Volt\{
    state,
    mount
};

state(['name', 'email', 'user']);

mount(function(){
    $this->name = $this->user->name;
    $this->email = $this->user->email;
});

$save = function(){
    $this->user->update([
        'name' => $this->name
    ]);

    Flux::toast('User updated.');
}

?>

<div class="min-w-96">

    <div class="mb-6">
        <flux:heading size="lg">Edit {{ $user->name }}</flux:heading>
    </div>
    <flux:separator variant="subtle" />
    <div class="my-6 space-y-4">
        <flux:input wire:model="name" label="Name"/>
        <flux:input wire:model="email" type="email" label="Email" disabled />
    </div>
    <flux:separator variant="subtle" />
    <div class="mt-6 flex justify-end">
       <flux:button wire:click="save" variant="primary">Save</flux:button>
    </div>
</div>