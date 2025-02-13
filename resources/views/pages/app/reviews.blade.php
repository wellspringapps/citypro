<?php

use function Laravel\Folio\name;

name('portal.reviews');


use function Livewire\Volt\{
    usesPagination,
    computed,
    on
};

usesPagination();


$reviews = computed(function(){
    return auth()->user()->listing->reviews()->orderBy('created_at', 'desc')->paginate(10);
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
        @if(count($this->reviews))
        <flux:table :paginate="$this->reviews">
            <flux:columns>
                <flux:column>Name</flux:column>
                <flux:column>Email</flux:column>
                <flux:column>Showing</flux:column>
                <flux:column>Sent</flux:column>
            </flux:columns>

            <flux:rows>
                @foreach ($this->reviews as $review)
                    <flux:row :key="$review->id">
                        <flux:cell class="flex items-center gap-3">
                            {{ $review->name }}
                        </flux:cell>

                        <flux:cell class="whitespace-nowrap">{{ $review->email }}</flux:cell>
                        <flux:cell class="whitespace-nowrap">
                            @if($review->show)
                                <flux:icon.check-badge />
                            @endif

                        </flux:cell>
                        <flux:cell class="whitespace-nowrap">{{ $review->created_at->format('m/j/y') }}</flux:cell>
                        <flux:cell>
                            <livewire:reviews.modal :$review />
                        </flux:cell>
                    </flux:row>
                @endforeach
            </flux:rows>
        </flux:table>
        @else
        <div class="text-center py-12 border rounded-xl">
            <flux:icon.star class="size-8 mx-auto"/>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No Reviews</h3>
            <p class="mt-1 text-sm text-gray-500"></p>
            
          </div>
        @endif
    </div>
    @endvolt
</x-layouts.portal>