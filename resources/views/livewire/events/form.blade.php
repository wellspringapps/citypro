<?php

use App\Models\Event;
use function Livewire\Volt\{state, rules, usesFileUploads, computed, mount};

usesFileUploads();

state(['event', 'image', 'name', 'description', 'start', 'end']);

rules([
    'image' => 'required',
    'name' => 'required',
    'description' => 'nullable',
    'start' => 'required',
    'end' => 'required',
]);

mount(function () {
    if ($this->event) {
        $this->image = $this->event->image;
        $this->name = $this->event->name;
        $this->description = $this->event->description;
        $this->start = $this->event->start->format('Y-m-d H:i:s');
        $this->end = $this->event->end->format('Y-m-d H:i:s');
    }
});

$type = computed(fn () => $this->event != null ? 'update' : 'create');

$create = function () {
    $authUser = auth()->user();
    $data = $this->validate();

    $data['user_id'] = $authUser->id;
    $data['image'] = $data['image']->store('event-photos/' . $authUser->listing->public_id);

    $event = $authUser->listing->events()->create($data);

    Flux::modal('create-event')->close();
    Flux::toast('Event created.');

    $this->dispatch('event-updated');
};

$update = function () {
    $authUser = auth()->user();
    $data = $this->validate();

    if (! is_string($data['image']) && $data['image']) {
        $data['image'] = $data['image']->store('event-photos/' . $authUser->listing->public_id);
    }

    $event = $this->event->update($data);

    Flux::modal('update-event-' . $this->event->public_id)->close();
    Flux::toast('Event updated.');

    $this->dispatch('event-updated');
};

$remove = function () {
    $this->event->delete();

    Flux::modal('update-event-' . $this->event->public_id)->close();
    Flux::toast('Event removed.');

    $this->dispatch('event-updated');
};

?>

<div>
    <div>
        <flux:heading size="lg">{{ ucfirst($this->type) }} Event</flux:heading>
        <flux:subheading></flux:subheading>
    </div>
    <div class="mt-4 space-y-4">
        @if ($this->type == 'update')
            <div x-data="{ edit: false }">
                <div x-show="edit">
                    <flux:input wire:model="image" label="Image" type="file" />
                    <div @click="edit = false" class="cursor-pointer py-4 hover:text-blue-500">Show Current Image</div>
                </div>
                <div x-show="!edit" @dblClick="edit = true">
                    <div class="text-xs">Double click image to edit</div>
                    <img src="{{ asset($event->image) }}" class="aspect-video w-64 rounded object-cover" />
                </div>
            </div>
        @else
            <flux:input wire:model="image" label="Image" type="file" />
        @endif
        <flux:input wire:model="name" label="Name" placeholder="Give your event a Name." />
        <flux:textarea wire:model="description" label="Description" />
        <flux:input wire:model="start" label="Start Time" type="datetime-local" />
        <flux:input wire:model.blur="end" label="End Time" type="datetime-local" />
    </div>

    <div class="mt-4 flex">
        @if ($this->type == 'update')
            <flux:modal.trigger name="confirm-{{ $event->public_id }}">
                <flux:button variant="danger" icon="trash">Remove</flux:button>
            </flux:modal.trigger>
        @endif

        <flux:spacer />

        <flux:button wire:click="{{ $this->type }}" variant="primary">Save</flux:button>
    </div>

    @if ($this->type == 'update')
        <flux:modal name="confirm-{{ $event->public_id }}">
            <div>
                <flux:heading size="lg">Are you sure?</flux:heading>
                <flux:subheading>Please confirm that you want to remove this event.</flux:subheading>
            </div>

            <div class="mt-4 flex justify-end">
                <flux:button wire:click="remove" variant="primary">Confirm</flux:button>
            </div>
        </flux:modal>
    @endif
</div>
