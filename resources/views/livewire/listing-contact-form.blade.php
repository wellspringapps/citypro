<?php

use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Mail;
use App\Mail\ListingContactFormSubmission;
use function Livewire\Volt\{
    state,
    rules
};

state(['name', 'email', 'message', 'listing']);

rules(['name' => 'required', 'email' => 'required|email', 'message' => 'required']);

$send = function(){   
    $this->validate();

    $listingOwner = $this->listing->user;

    $cs = ContactSubmission::create([
        'listing_id' => $this->listing->id,
        'name' => $this->name,
        'email' => $this->email,
        'message' => $this->message
    ]);

    Mail::to($listingOwner)->send(new ListingContactFormSubmission($this->listing, $cs));

    Flux::toast('Message sent.');

    $this->resetExcept('listing');
}

?>

<div class="mx-auto mt-16 p-4 bg-gray-100 shadow rounded-xl sm:mt-20">
    <div class="space-y-4">
      <flux:heading size="xl" level="2">Contact {{ $listing->title }}</flux:heading>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <flux:input wire:model="name" label="Full Name" />
        <flux:input wire:model="email" label="Email" />
        
      </div>
      
      <flux:textarea wire:model="message" label="Message" />

      <div class="flex justify-end">
        <flux:button wire:click="send" variant="primary" icon="paper-airplane">Send</flux:button>
      </div>
    </div>
</div>