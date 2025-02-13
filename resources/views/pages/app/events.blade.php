<?php

use function Laravel\Folio\name;

name('portal.events');

use App\Models\Event;
use function Livewire\Volt\{
    computed,
    on
};

on([
    'event-updated' => function(){
    }
]);

$limit = computed(function(){
    return 3;
});


$events = computed(function(){
    $listingId = auth()->user()->listing->id;
    return Event::where('listing_id', $listingId)->get();
});

$resetEvents = function(){
    unset($this->events);
    Flux::toast('Events reset.');
};

?>

<x-layouts.portal>
    @if(auth()->user()->listing->pro)
    @volt
    <div>
        <div class="flex justify-between">
            <div>
                <flux:heading size="xl" level="1">Manage Events</flux:heading>
                <flux:subheading size="lg" class="mb-6">Add events to our community calendar.</flux:subheading>
            </div>
            
            
            @if(count($this->events) < $this->limit)
                <flux:modal.trigger name="create-event">
                    <flux:button variant="primary" icon="plus">Create Event</flux:button>
                </flux:modal.trigger>
            @else
                <div>
                    <div>You have reached your limit of events.</div>
                    <div>Delete one to create another.</div>
                </div>
            @endif

        </div>

        <flux:separator variant="subtle" />

        <div>
            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                @forelse($this->events as $event)
                    <livewire:events.item :key='$event->public_id . now()' :$event />
                @empty
                    <flux:modal.trigger name="create-event">
                        <button type="button" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden">
                            <flux:icon.calendar class="size-12 mx-auto text-gray-300" />
                            <span class="mt-2 block text-sm font-semibold text-gray-300">Add an event</span>
                        </button>
                    </flux:modal>
                @endforelse
              </div>
        </div>
        @if(count($this->events) < $this->limit)
        <flux:modal name="create-event" variant="flyout">
            <livewire:events.form />
        </flux:modal>
        @endif
        
    </div>
    @endvolt
    @endif
</x-layouts.portal>