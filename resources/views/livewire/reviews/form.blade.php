<?php

use App\Models\Review;
use function Livewire\Volt\{
    state,
    rules
};

state(['listing', 'review', 'rating', 'name', 'email']);

rules([ 
    'review' => 'required', 
    'rating' => 'required', 
    'name' => 'required', 
    'email' => 'required'
]);

$send = function(){

    $this->validate();


    $review = Review::create([
        'listing_id' => $this->listing->id,
        'name' => $this->name,
        'email' => $this->email,
        'review' => $this->review,
        'rating' => $this->rating
    ]);

    $avgRating = $this->listing->reviews()->avg('rating');

    $this->listing->rating = $avgRating;
    $this->listing->save();

    Flux::modals()->close();
    Flux::toast('Thank you for your review!');

    $this->dispatch('review-sent');
    
}


?>

<div>
    <flux:modal.trigger name="review-form">
        <flux:button icon="star">Leave Review</flux:button>
    </flux:modal.trigger>
    <flux:modal name="review-form" variant="flyout">
        <div class="mb-6">
            <flux:heading size="lg">Leave a Review</flux:heading>
            <flux:subheading>You are reviewing <strong>{{ $listing->title }}</strong></flux:subheading>
        </div>
        <flux:separator variant="subtle" />
        <div class="space-y-4">
            
            <flux:radio.group wire:model="rating" label="Rating" variant="cards" class="flex-col">
                <flux:radio value="5" >
                    <flux:radio.indicator />

                    <div class="flex-1 flex">
                        <flux:icon.star />
                        <flux:icon.star />
                        <flux:icon.star />
                        <flux:icon.star />
                        <flux:icon.star />
                    </div>
                </flux:radio>
                <flux:radio value="4" >
                    <flux:radio.indicator />

                    <div class="flex-1 flex">
                        <flux:icon.star />
                        <flux:icon.star />
                        <flux:icon.star />
                        <flux:icon.star />
                    </div>
                </flux:radio>
                <flux:radio value="3" >
                    <flux:radio.indicator />

                    <div class="flex-1 flex">
                        <flux:icon.star />
                        <flux:icon.star />
                        <flux:icon.star />
                    </div>
                </flux:radio>
                <flux:radio value="2" >
                    <flux:radio.indicator />

                    <div class="flex-1 flex">
                        <flux:icon.star />
                        <flux:icon.star />
                    </div>
                </flux:radio>
                <flux:radio value="1" >
                    <flux:radio.indicator />

                    <div class="flex-1 flex">
                        <flux:icon.star />
                    </div>
                </flux:radio>
            </flux:radio.group>
            <flux:input wire:model="name" label="Your Name"/>
            <flux:input wire:model="email" type="email" label="Your Email"/>
            <flux:textarea wire:model="review" label="Review"/>
        </div>
        <flux:separator variant="subtle" />
        <div class="mt-6 flex justify-end">
            <flux:button wire:click="send" variant="primary">Send</flux:button>
        </div>
    </flux:modal>
</div>