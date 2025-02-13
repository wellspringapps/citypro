<?php

use function Laravel\Folio\{name, middleware};

name('portal.coupons');

use App\Models\Coupon;
use function Livewire\Volt\{
    computed,
    on
};

on([
    'coupon-created' => function(){ unset($this->coupons); },
    'coupon-updated' => function(){ unset($this->coupons); }
]);


$limit = computed(function(){
    return 3;
});

$coupons = computed(function(){
    return auth()->user()->listing->coupons;
});

?>

<x-layouts.portal>

    @if(auth()->user()->listing->pro)
    @volt
    <div>
        <div class="flex justify-between">
            <div>
                <flux:heading size="xl" level="1">Manage Coupons</flux:heading>
                <flux:subheading size="lg" class="mb-6">Offer amazing deals to your potential customers.</flux:subheading>
            </div>
            
            <flux:modal.trigger name="create-coupon">
                <flux:button variant="primary" icon="plus">Create Coupon</flux:button>
            </flux:modal.trigger>
        </div>

        <flux:separator variant="subtle" />

        <div>
            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8">
                @forelse($this->coupons as $coupon)
                    <livewire:coupons.item :key='$coupon->public_id . now()' :$coupon />
                @empty
                    <flux:modal.trigger name="create-coupon">
                        <button type="button" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden">
                            <flux:icon.percent-badge class="size-12 mx-auto text-gray-300" />
                            <span class="mt-2 block text-sm font-semibold text-gray-300">Add a coupon</span>
                        </button>
                    </flux:modal>
                @endforelse
            </div>
        </div>
        @if(count($this->coupons) < $this->limit)
        <flux:modal name="create-coupon" variant="flyout">
            <livewire:coupons.form />
        </flux:modal>
        @endif
        
    </div>
    @endvolt

    @endif
</x-layouts.portal>