<?php

use App\Models\Coupon;
use function Livewire\Volt\{state, rules, usesFileUploads, computed, mount};

usesFileUploads();

state(['coupon', 'image', 'name', 'description', 'terms']);

rules([
    'image' => 'required',
    'name' => 'required',
    'description' => 'required',
    'terms' => 'required',
]);

mount(function () {
    if ($this->coupon) {
        $this->image = $this->coupon->image;
        $this->name = $this->coupon->name;
        $this->description = $this->coupon->description;
        $this->terms = $this->coupon->terms;
    }

    $this->terms = $this->terms ?? 'Valid for a limited time only.';
});

$type = computed(fn () => $this->coupon != null ? 'update' : 'create');

$create = function () {
    $authUser = auth()->user();
    $data = $this->validate();

    $data['user_id'] = $authUser->id;
    $data['image'] = $data['image']->store('coupon-photos/' . $authUser->listing->public_id);

    $coupon = $authUser->listing->coupons()->create($data);

    Flux::modal('create-coupon')->close();
    Flux::toast('Coupon created.');

    $this->dispatch('coupon-created');
};

$update = function () {
    $authUser = auth()->user();
    $data = $this->validate();

    if (! is_string($data['image']) && $data['image']) {
        $data['image'] = $data['image']->store('coupon-photos/' . $authUser->listing->public_id);
    }

    $coupon = $this->coupon->update($data);

    Flux::modal('update-coupon-' . $this->coupon->public_id)->close();
    Flux::toast('Coupon updated.');

    $this->dispatch('coupon-updated');
};

$remove = function () {
    $this->coupon->delete();

    Flux::modal('update-coupon-' . $this->coupon->public_id)->close();
    Flux::toast('Coupon removed.');

    $this->dispatch('coupon-updated');
};

?>

<div>
    <div>
        <flux:heading size="lg">{{ ucfirst($this->type) }} Coupon</flux:heading>
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
                    <img src="{{ asset($coupon->image) }}" class="aspect-video w-64 rounded object-cover" />
                </div>
            </div>
        @else
            <flux:input wire:model="image" label="Image" type="file" />
        @endif
        <flux:input wire:model="name" label="Name" placeholder="Give your coupon a Name." />
        <flux:input
            wire:model="description"
            label="Description"
            placeholder="What does your customer get with this coupon?"
        />
        <flux:input wire:model="terms" label="Terms" placeholder="What are the terms of using this coupon?" />
    </div>

    <div class="mt-4 flex">
        @if ($this->type == 'update')
            <flux:modal.trigger name="confirm-{{ $coupon->public_id }}">
                <flux:button variant="danger" icon="trash">Remove</flux:button>
            </flux:modal.trigger>
        @endif

        <flux:spacer />

        <flux:button wire:click="{{ $this->type }}" variant="primary">Save</flux:button>
    </div>

    @if ($this->type == 'update')
        <flux:modal name="confirm-{{ $coupon->public_id }}">
            <div>
                <flux:heading size="lg">Are you sure?</flux:heading>
                <flux:subheading>Please confirm that you want to remove this coupon.</flux:subheading>
            </div>

            <div class="mt-4 flex justify-end">
                <flux:button wire:click="remove" variant="primary">Confirm</flux:button>
            </div>
        </flux:modal>
    @endif
</div>
