<?php
use App\Models\Listing;

use function Laravel\Folio\name;

name('admin.listings');

use function Livewire\Volt\{usesPagination, computed, on};

usesPagination();

on([
    'listing-update' => function () {
        unset($this->listings);
    },
]);

$listings = computed(function () {
    return Listing::paginate(10);
});

?>

<x-layouts.portal>
    <div class="flex justify-between">
        <div>
            <flux:heading size="xl" level="1">Listings</flux:heading>
            <flux:subheading size="lg" class="mb-6">Add user and create listing</flux:subheading>
        </div>

        <flux:modal.trigger name="create-listing">
            <flux:button variant="primary" icon="plus">Create listing</flux:button>
        </flux:modal.trigger>
    </div>

    <flux:separator variant="subtle" />

    @volt
        <div class="mt-12">
            @if (count($this->listings))
                <flux:table :paginate="$this->listings">
                    <flux:table.columns>
                        <flux:table.column>Name</flux:table.column>
                        <flux:table.column>Email</flux:table.column>
                        <flux:table.column>Pro</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($this->listings as $listing)
                            <flux:table.row :key="$listing->public_id">
                                <flux:table.cell class="flex items-center gap-3">
                                    <a
                                        href="{{ route('admin.listing', ['listing' => $listing]) }}"
                                        :key="$listing->id"
                                    >
                                        {{ $listing->title }}
                                    </a>
                                </flux:table.cell>

                                <flux:table.cell class="whitespace-nowrap">
                                    @if ($listing->user)
                                        <flux:modal.trigger name="edit-user-{{ $listing->user->public_id }}">
                                            {{ $listing->user->email }}
                                        </flux:modal.trigger>
                                    @else
                                        <flux:modal.trigger name="add-user-{{ $listing->public_id }}">
                                            Connect user to listing
                                        </flux:modal.trigger>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell class="whitespace-nowrap">
                                    @if ($listing->pro)
                                        <flux:icon.check-badge />
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
                @foreach ($this->listings as $listing)
                    @if ($listing->user)
                        <livewire:admin.edit-user :key="$listing->public_id" :user="$listing->user" />
                    @else
                        <livewire:admin.add-user :key="$listing->public_id" :listing="$listing" />
                    @endif
                @endforeach
            @else
                <div class="rounded-xl border py-12 text-center">
                    <flux:icon.at-symbol class="mx-auto size-8" />
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No Listings</h3>
                    <p class="mt-1 text-sm text-gray-500"></p>
                </div>
            @endif
            <livewire:admin.create-listing />
        </div>
    @endvolt
</x-layouts.portal>
