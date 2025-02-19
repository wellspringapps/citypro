<?php

use function Laravel\Folio\name;

name('portal.reviews');

use function Livewire\Volt\{usesPagination, computed, on};

usesPagination();

$reviews = computed(function () {
    return auth()
        ->user()
        ->listing->reviews()
        ->orderBy('created_at', 'desc')
        ->paginate(10);
});

?>

<x-layouts.portal>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1">Manage Reviews</flux:heading>
            <flux:subheading size="lg" class="mb-6">Manage messages sent to you via your listing.</flux:subheading>
        </div>
    </div>

    <flux:separator variant="subtle" />

    @volt
        <div class="mt-12">
            @if (count($this->reviews))
                <flux:table :paginate="$this->reviews">
                    <flux:table.columns>
                        <flux:table.column>Name</flux:table.column>
                        <flux:table.column>Email</flux:table.column>
                        <flux:table.column>Showing</flux:table.column>
                        <flux:table.column>Sent</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($this->reviews as $review)
                            <flux:table.row :key="$review->id">
                                <flux:table.cell class="flex items-center gap-3">
                                    {{ $review->name }}
                                </flux:table.cell>

                                <flux:table.cell class="whitespace-nowrap">{{ $review->email }}</flux:table.cell>
                                <flux:table.cell class="whitespace-nowrap">
                                    @if ($review->show)
                                        <flux:icon.check-badge />
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell class="whitespace-nowrap">
                                    {{ $review->created_at->format('m/j/y') }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    <livewire:reviews.modal :$review />
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @else
                <div class="rounded-xl border py-12 text-center">
                    <flux:icon.star class="mx-auto size-8" />
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No Reviews</h3>
                    <p class="mt-1 text-sm text-gray-500"></p>
                </div>
            @endif
        </div>
    @endvolt
</x-layouts.portal>
