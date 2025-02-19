<?php
use function Livewire\Volt\{state};

state(['coupon', 'readonly']);

?>
<div>
    @if(!$readonly)
    <div>
        <flux:modal.trigger name="update-coupon-{{ $coupon->public_id }}">
            <div class="group relative">
                <img src="{{ asset($coupon->image )}}" class=" rounded-sm shadow-sm aspect-video w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 ">
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                          
                            <span aria-hidden="true" class="absolute inset-0"></span>
                            {{ $coupon->name }}
                         
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">{{ $coupon->description }}</p>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">{{ $coupon->terms }}</p>
                </div>
            </div>
        </flux:modal.trigger>
        <flux:modal name="update-coupon-{{ $coupon->public_id }}" variant="flyout">
            <livewire:coupons.form :$coupon />
        </flux:modal>
    @else
        <div class="group relative">
            <img src="{{ asset($coupon->image )}}" class=" rounded-sm shadow-sm aspect-video w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 ">
            <div class="mt-4 flex justify-between">
                <div>
                    <h3 class="text-sm text-gray-700">
                        
                        <span aria-hidden="true" class="absolute inset-0"></span>
                        {{ $coupon->name }}
                        
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $coupon->description }}</p>
                </div>
                <p class="mt-1 text-sm text-gray-500">{{ $coupon->terms }}</p>
            </div>
        </div>
    @endif
</div>