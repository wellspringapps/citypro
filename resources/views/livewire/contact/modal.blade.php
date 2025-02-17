<?php

use function Livewire\Volt\{state};

state(['submission']);

$archive = function () {
    $this->submission->delete();

    Flux::modals()->close();
    Flux::toast('Message archived.');

    $this->dispatch('message-archived');
};

?>

<div>
    <flux:modal.trigger name="view-submission-{{ $submission->public_id }}">
        <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
    </flux:modal.trigger>
    <flux:modal name="view-submission-{{ $submission->public_id }}" variant="flyout">
        <div class="mb-6">
            <flux:heading size="lg">{{ $submission->name }}</flux:heading>
            <flux:subheading>{{ $submission->email }}</flux:subheading>
        </div>
        <flux:separator variant="subtle" />
        <div class="my-6">
            {{ $submission->message }}
        </div>
        <flux:separator variant="subtle" />
        <div class="mt-6 flex justify-end">
            <flux:modal.trigger name="view-submission-{{ $submission->public_id }}-confirm">
                <flux:button icon="trash" variant="danger">Archive</flux:button>
            </flux:modal.trigger>
        </div>
    </flux:modal>
    <flux:modal name="view-submission-{{ $submission->public_id }}-confirm">
        <div class="mb-6">
            <flux:heading size="lg">Are you sure?</flux:heading>
            <flux:subheading>Please confirm you want to remove this message.</flux:subheading>
        </div>
        <div class="mt-6 flex justify-end">
            <flux:button wire:click="archive" variant="primary">Confirm</flux:button>
        </div>
    </flux:modal>
</div>
