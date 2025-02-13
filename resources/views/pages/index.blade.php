<?php

use App\Models\Listing;
use function Livewire\Volt\{
    computed,
    state
};

state(['search', 'categories'])->url();

$listings = computed(function(){
    $listingsSearch = Listing::select('listings.*')
    ->join('users', 'users.id', '=', 'listings.user_id');
    /*->orderByRaw('
        CASE 
            WHEN users.forever = 1 OR users.stripe_id IS NOT NULL THEN 1 
            ELSE 0 
        END DESC
    ')*/

    $listingsSearch->orderBy('rating', 'desc');


    return $listingsSearch->limit(12)->get();
});

?>
<x-layouts.public>
    <x-slot name="title">Find an Indianapolis Pro on CircleCity.Pro</x-slot>
    <x-slot name="description">Find local professionals in Indianapolis with CircleCity.Pro. Search top-rated businesses, read reviews, and connect with trusted pros for all your needs.</x-slot>
    <div class="bg-white">

        <div class="relative ">
          <div class="mx-auto container">
            <div class="relative z-10 pt-1 lg:w-full lg:max-w-2xl">
              <div class="relative px-6 py-12 sm:py-40 lg:px-8 lg:py-56 lg:pr-0">
                <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl">
                  <h1 class="text-7xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-7xl">Find an Indianapolis Pro</h1>
                  <h2 class="mt-8 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">Easily discover top-rated businesses in Indianapolis and connect with the right professionals for your needs.</h2>
                  <div class="mt-10 flex items-center gap-x-6">
                    <flux:button href="/search" variant="primary">Find a Pro</flux:button>
                    <flux:button href="/claim-listing" >List Your Business</flux:button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="aspect-3/2 object-cover lg:aspect-auto lg:size-full" src="/home.jpg" alt="">
          </div>
        </div>
    </div>
    @volt
    <div>
        <div>
          <h2 class="text-center text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-6xl my-24">Featured Pros</h2>
        </div>
        <div class="mx-auto container grid grid-cols-3 my-12 px-6 lg:px-8 gap-12">
            
            @foreach($this->listings as $listing)
            <article class="flex flex-col items-start justify-between w-full">
                <a class="block w-full" href="{{ route('public.listing', ['listing' => $listing]) }}">
                  <div class="relative w-full">
                    @if($listing->header_photo )
                      <img
                          src="{{ asset($listing->header_photo) }}"
                          alt="Business cover"
                          class="aspect-video w-full rounded-2xl bg-gray-100 object-cover sm:aspect-2/1 lg:aspect-3/2"
                      />
                      @else
                      <img
                          src="/no-pic.png"  
                          alt="Business cover"
                          class="aspect-video w-full rounded-2xl bg-gray-100 object-cover sm:aspect-2/1 lg:aspect-3/2"
                      />
                    @endif
                    <div class="absolute inset-0 rounded-2xl ring-1 ring-gray-900/10 ring-inset"></div>
                  </div>
                  <div class="max-w-xl">
                    <div class="group relative">
                      <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                        <span class="absolute inset-0"></span>
                        {{ $listing->title }}
                      </h3>
                    </div>
                    <p>{{ limitString($listing->description, 100) }}</p>
                    <div class="mt-4 flex items-center gap-x-2 text-xs">
                      @foreach($listing->categories ?? [] as $category)
                          <span class="bg-gray-200 p-1 rounded-full text-[0.45rem] font-medium text-gray-600">{{ $category }}</span>
                      @endforeach
                  </div>
                </div>
              </a>
            </article>
         

            @endforeach

            
      
            <!-- More posts... -->
          </div>

          <div class="flex justify-center">
            <flux:button href="/search" variant="primary">Find a Pro</flux:button>
          </div>
                    

          {{-- @php
              $categories = getBusinessCategories();
            @endphp
            <div class="flex justify-center container mx-auto flex-wrap" >
              @foreach($categories as $category)
                <a href="/search?categories[]=%20{{ $category }}%20">
                  <span class="bg-gray-200 px-2 py-1 m-2 rounded-full text-xs font-medium text-gray-600">{{ $category }}</span>
                </a>
              @endforeach
            </div> --}}
            

        </div>
        @endvolt
      
</x-layouts.public>