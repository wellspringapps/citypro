<?php
use function Livewire\Volt\{state};

state(['event', 'readonly']);

?>
<div>
    @if(!$readonly)
    <flux:modal.trigger name="update-event-{{ $event->public_id }}">
        <div class="group relative">
            <img src="{{ asset($event->image )}}" alt="Front of men&#039;s Basic Tee in black." class=" rounded shadow aspect-video w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 ">
            <div class="mt-4 flex justify-between">
                <div>
                <h3 class="text-sm text-gray-700">
                    <a href="#">
                    <span aria-hidden="true" class="absolute inset-0"></span>
                    {{ $event->name }}
                    </a>
                </h3>
                <p class="mt-1 text-sm text-gray-500">{{ $event->description}}</p>
                </div>
                <p class="text-xs text-gray-900">{{ formatHumanReadableDateRange($event->start, $event->end) }}</p>
            </div>
        </div>
    </flux:modal.trigger>
    <flux:modal name="update-event-{{ $event->public_id }}" variant="flyout">
        <livewire:events.form :$event />
    </flux:modal>
    @else
        <div class="group relative">
            <img src="{{ asset($event->image )}}" alt="Front of men&#039;s Basic Tee in black." class=" rounded shadow aspect-video w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 ">
            <div class="mt-4 flex justify-between">
                <div>
                <h3 class="text-sm text-gray-700">
            
                    <span aria-hidden="true" class="absolute inset-0"></span>
                    {{ $event->name }}
                </h3>
                <p class="mt-1 text-sm text-gray-500">{{ $event->description}}</p>
                </div>
                <p class="text-xs text-gray-900">{{ formatHumanReadableDateRange($event->start, $event->end) }}</p>
            </div>
        </div>
    @endif
</div>