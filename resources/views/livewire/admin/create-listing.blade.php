<?php

use App\Models\Listing;
use function Livewire\Volt\{
    state,
    mount
};

state(['title']);

$save = function(){
    $listing = Listing::create([
        'title' => $this->title
    ]);

    Flux::toast('Listing created.');
    Flux::modals()->close();

    $this->dispatch('listing-update');
}

?>

<flux:modal name="create-listing">
    <div class="min-w-96">

        <div class="mb-6">
            <flux:heading size="lg">Create Listing</flux:heading>
        </div>
        <flux:separator variant="subtle" />
        <div class="my-6 space-y-4">
            <flux:input wire:model="title" label="title"/>
        </div>
        <flux:separator variant="subtle" />
        <div class="mt-6 flex justify-end">
        <flux:button wire:click="save" variant="primary">Save</flux:button>
        </div>
    </div>
</flux:modal>