<?php

use function Livewire\Volt\{
    state,
    mount
};

state(['review', 'response', 'show']);


mount(function(){
    $this->response = $this->review->response;
    $this->show = $this->review->show ? true : false;

});

$save = function(){
    $this->review->response = $this->response;
    $this->review->show = $this->show;

    $this->review->save();

    Flux::modals()->close();
    Flux::toast('Review saved.');
};


$resetOnClose =  function(){
    $this->response = $this->review->response;
    $this->show = $this->review->show ? true : false;
}


?>

<div>
    <flux:modal.trigger name="view-review-{{ $review->public_id }}">
        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
    </flux:modal.trigger>
    <flux:modal name="view-review-{{ $review->public_id }}" variant="flyout" @close="resetOnClose">
        <div class="mb-6">
            <flux:heading size="lg">Review from {{ $review->name }}</flux:heading>
            <div class="flex items-center gap-1">
                @if($review->rating > 0)
                    @for($i = 0; $i < $review->rating; $i++)
                        <flux:icon.star variant="solid" class="text-black size-4" />
                    @endfor
                @endif
            </div>
        </div>
        <flux:separator variant="subtle" />
        <flux:card class="my-6">
            {{ $review->review }}
        </flux:card>
        <div class="my-6 space-y-4">
            <flux:switch wire:model="show" label="Show Review on Listing" />
            <flux:textarea wire:model="response" label="Your Response"/>
        </div>
        <flux:separator variant="subtle" />
        <div class="mt-6 flex justify-end">
           <flux:button wire:click="save" variant="primary">Save</flux:button>
        </div>
    </flux:modal>
</div>