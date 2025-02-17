<?php

use function Laravel\Folio\name;

name('portal.messages');

use function Livewire\Volt\{usesPagination, computed, on};

on([
    'message-archived' => function () {
        unset($this->submissions);
    },
]);

$submissions = computed(function () {
    return auth()
        ->user()
        ->listing->contactSubmissions()
        ->orderBy('created_at', 'desc')
        ->paginate(10);
});

?>

<x-layouts.portal>
    @if (auth()->user()->listing->pro)
        <div class="flex justify-between">
            <div>
                <flux:heading size="xl" level="1">Manage Messages</flux:heading>
                <flux:subheading size="lg" class="mb-6">Manage messages sent to you via your listing.</flux:subheading>
            </div>
        </div>

        <flux:separator variant="subtle" />

        @volt
            <div class="mt-12">
                @if (count($this->submissions))
                    <flux:table :paginate="$this->submissions">
                        <flux:columns>
                            <flux:column>Name</flux:column>
                            <flux:column>Email</flux:column>
                            <flux:column>Sent</flux:column>
                        </flux:columns>

                        <flux:rows>
                            @foreach ($this->submissions as $submission)
                                <flux:row :key="$submission->id">
                                    <flux:cell class="flex items-center gap-3">
                                        {{ $submission->name }}
                                    </flux:cell>

                                    <flux:cell class="whitespace-nowrap">{{ $submission->email }}</flux:cell>
                                    <flux:cell class="whitespace-nowrap">
                                        {{ $submission->created_at->format('m/j/y') }}
                                    </flux:cell>

                                    <flux:cell>
                                        <livewire:contact.modal :$submission />
                                    </flux:cell>
                                </flux:row>
                            @endforeach
                        </flux:rows>
                    </flux:table>
                @else
                    <div class="rounded-xl border py-12 text-center">
                        <flux:icon.at-symbol class="mx-auto size-8" />
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">No Messages</h3>
                        <p class="mt-1 text-sm text-gray-500"></p>
                    </div>
                @endif
            </div>
        @endvolt
    @endif
</x-layouts.portal>
