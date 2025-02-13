<?php

use function Laravel\Folio\name;

name('public.listing');

?>
<x-layouts.public>
  <div class="relative">
    <div class="h-64 md:h-96 w-full bg-gray-200 relative ">
        @if($listing->header_photo )
        <img
            src="{{ asset($listing->header_photo) }}"
            alt="Business cover"
            class="w-full h-full object-cover"
        />
        @else
        <img
            src="/no-pic.png"
            alt="Business cover"
            class="w-full h-full object-contain"
        />

        @endif
        
        <div class="container mx-auto px-4 mt-20">
            <div class="absolute -bottom-16 border-4 border-white rounded-xl overflow-hidden shadow-lg">
                <img
                        src="{{ $listing->listing_photo ? asset($listing->listing_photo) : 'https://ui-avatars.com/api/?name=' . $listing->title . '&color=FFFFFF&background=1B1C20' }}"
                        alt="Business logo"
                        class="w-32 h-32 object-cover"
                    />
            </div>
        </div>
    </div>
    
    <div class="container mx-auto px-4 mt-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex flex-col gap-y-2">
                <div>
                    <h1 class="text-3xl font-bold">{{ $listing->title }}</h1>
                </div>
                <div>
                  <div class="flex flex-wrap gap-4 text-sm">
                    @if($listing->categories)
                        @foreach($listing->categories as $category)
                            <div class="bg-gray-200 px-2 py-1 rounded-full text-xs font-medium text-gray-600">{{ $category }}</div>
                        @endforeach
                    @endif
                    
                  </div>
                </div>
                <div class="flex items-center gap-2 text-gray-600 mt-2">
                   
                    <flux:tooltip content="Avg Rating - {{ $listing->rating }} Stars">
                        <div class="flex items-center gap-1 bg-[#FBC02D] px-2 py-1 rounded-full text-sm font-medium text-black">
                            @if($listing->rating > 0)
                            @for($i = 0; $i < $listing->rating; $i++)
                                <flux:icon.star variant="solid" class="text-black size-4" />
                            @endfor
                            @else
                                <flux:icon.star class="text-black size-4" />
                            @endif
                        </div>
                    </flux:tooltip>
                    
                    <a href="#reviews">{{ $listing->reviews()->count() ?: 'No' }} reviews</a>
                    
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($listing->pro)
                @if($listing->phone)
                  <flux:button target="_blank" href="tel:{{ $listing->phone }}" icon="phone">Call</flux:button>
                @endif
                
                @if($listing->email)
                  <flux:button target="_blank" href="mailto:{{ $listing->email }}" icon="at-symbol">Email</flux:button>
                @endif
                @if($listing->website)
                  <flux:button target="_blank" :href="$listing->website" icon="globe-alt">Go to Website</flux:button>
                @endif
                @endif
                <livewire:reviews.form :$listing />
            </div>
        
        </div>
    </div>
</div>

<!-- Business Details -->
<div class="relative container mx-auto px-4 py-8 mb-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
          @if($listing->description)
            <section class="mb-8">
                <div>
                    <h2 class="text-2xl font-semibold mb-4" >About</h2>
                    <div class="text-gray-600 leading-relaxed prose">{!! $listing->description !!}</div>
                </div>
            </section>
            @endif

            @if($listing->pro)

                @if($listing->media?->isNotEmpty() ?? false)
                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold mb-4">Media</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($listing->media as $idx => $media)
                                <div class="aspect-square bg-black rounded-lg overflow-hidden hover-scale relative ">
                                    @if(isImage(asset($media)))
                                        <img src="{{ asset( $media ) }}" class="w-full h-full object-cover glightbox" />
                                    @else
                                        <video src="{{ asset( $media ) }}" class="w-full h-full object-contain glightbox" controls></video>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            
                @if($listing->attachments?->isNotEmpty() ?? false)
                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold mb-4">Attachments</h2>
                        <div class="">
                            @foreach($listing->attachments ?? [] as $idx => $attachment)
                            <flux:card  class="mb-4">
                                <div class="flex justify-between p-4">
                                    <div>{{ $attachment['title'] }}</div>
                
                                
                                    <a href="{{ asset($attachment['path'])}}" target="_blank" >
                                    <flux:icon.cloud-arrow-down  class=" size-6 hover:text-red-500" />
                                    </a>
                                </div>
                            </flux:card>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif

            <section class="mb-8">
              <div class="flex justify-between">
                <h2 class="text-2xl font-semibold mb-4">Reviews</h2>
                <flux:modal.trigger name="review-form">
                    <flux:button icon="star">Leave Review</flux:button>
                </flux:modal.trigger>
              </div>
              <div class="">
                @forelse($listing->reviews()->where('show', true)->limit(5)->get() ?? [] as $idx => $review)
                  <flux:card  class="mb-4">
                    <div class="p-4">
                        <div class="text-xl my-4">{{ $review->review }}</div>
                        @php

                        $name = $review->name;
                        $parts = explode(' ', $name);
                        $inital = '';

                        if(count($parts) > 1){
                            $inital = substr($parts[count($parts) - 1], 0, 1);
                            $inital .= ".";
                        }

                        @endphp
                        <div class="text-sm">-{{ $parts[0] }} {{ $inital }}</div>
                    </div>
                  </flux:card>
                @empty
                <div class="text-center py-12 border rounded-xl">
                  <flux:icon.paper-airplane class="size-8 mx-auto"/>
                  <h3 class="mt-2 text-sm font-semibold text-gray-900">No Reviews</h3>
                  <p class="mt-1 text-sm text-gray-500">Your review could be the first!</p>
                  
                </div>
                
                @endforelse
              </div>
            </section>

            @if($listing->pro)
                @if($listing->events->isNotEmpty() ?? false)
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Events</h2>
                    <div class="grid grid-cols-3 gap-4">
                    @foreach($listing->events as $idx => $event)
                    <livewire:events.item :key='$event->public_id . now()' :$event readonly />
                    @endforeach
                    </div>
                </section>
            @endif


            @if($listing->coupons->isNotEmpty() ?? false)
                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Coupons</h2>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($listing->coupons()->get() as $idx => $coupon)
                        <livewire:coupons.item :key='$coupon->public_id . now()' :$coupon readonly />
                        @endforeach
                    </div>
                </section>
            @endif
          

            <livewire:listing-contact-form :$listing />
          @endif
          
        </div>

        <div>
            <flux:card>
                <h3 class="text-xl font-semibold mb-4">Business Info</h3>
                <div class="space-y-4">
                    @if($listing->pro)
                        @if($listing->website)
                            <div class="flex items-start gap-3">
                                <flux:icon.globe-alt />
                                <div>
                                    <h4 class="font-medium">Website</h4>
                                    <p  class="text-gray-600">{{ $listing->website }}</p>
                                </div>
                            </div>
                        @endif
                    @endif
        
                    @if($listing->address)
                    <div class="flex items-start gap-3">
                        <flux:icon.map-pin />
                        <div >
                            <h4 class="font-medium">Location</h4>
                            <p class="text-gray-600">{{ $listing->address }}</p>
                            
                        </div>
                    </div>
                    @endif
        
                    @if($listing->areas_served)
                        <div class="flex items-start gap-3">
                            <flux:icon.globe-americas />
                            <div >
                                <h4 class="font-medium">Areas Served</h4>
                                <p  class="text-gray-600">{{ $listing->areas_served ?? '-' }}</p>
                            </div>
                        </div>
                    @endif
    
                    <div class="flex items-start gap-3">
                        <flux:icon.clock />
                        <div>
                            <h4 class="font-medium">Hours</h4>
                            <div class="text-sm text-gray-600">
                                <div class="flex justify-between py-1">
                                    <span class="w-24">Monday</span>
                                    <span >{{ $listing->hours['monday'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between py-1" >
                                    <span  class="w-24">Tuesday</span>
                                    <span >{{ $listing->hours['tuesday'] ?? '' }}</span>
                                    
                                </div>
                                <div class="flex justify-between py-1" >
                                    <span class="w-24">Wednesday</span>
                                    <span>{{ $listing->hours['wednesday'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="w-24">Thursday</span>
                                    <span>{{ $listing->hours['thursday'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between py-1">
                                    <span class="w-24">Friday</span>
                                    <span >{{ $listing->hours['friday'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between py-1" >
                                    <span  class="w-24">Saturday</span>
                                    <span >{{ $listing->hours['saturday'] ?? '' }}</span>
                                  
                                </div>
                                <div class="flex justify-between py-1" >
                                    <span  class="w-24">Sunday</span>
                                    <span >{{ $listing->hours['sunday'] ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($listing->pro)
                        @if($listing->phone)
                            <div class="flex items-start gap-3">
                                <flux:icon.phone />
                                <div >
                                    <h4  class="font-medium">Phone</h4>
                                    <p class="text-gray-600">{{ $listing->phone ?? '-' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($listing->email)
                        <div class="flex items-start gap-3">
                            <flux:icon.envelope />
                            <div>
                                <h4 class="font-medium">Email</h4>
                                <pclass="text-gray-600">{{ $listing->email ?? '-' }}</p>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </flux:card>
        </div>
        
    </div>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    
        <script type="text/javascript">
          const lightbox = GLightbox();
        </script>
      </div>
</x-layouts.public>