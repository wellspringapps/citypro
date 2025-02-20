<?php

use App\Models\Listing;
use function Livewire\Volt\{
    computed,
    state
};

state(['search', 'categories']);

$listings = computed(function(){
    $listingsSearch = Listing::select('listings.*');
    //->join('users', 'users.id', '=', 'listings.user_id');
    /*->orderByRaw('
        CASE 
            WHEN users.forever = 1 OR users.stripe_id IS NOT NULL THEN 1 
            ELSE 0 
        END DESC
    ')*/

    if($this->search){
        $listingsSearch
            ->whereLike('title', '%'. $this->search .'%');
    }

    if($this->categories){
        $listingsSearch
            ->whereJsonContains('categories', $this->categories);
    }


    return $listingsSearch->get();
});


?>

<x-layouts.public>

    <x-slot name="title">Search</x-slot>
    @volt
    <div>
        @php
            $businessCategories = [
                "Accounting & Bookkeeping",
                "Adventure & Outdoor Activities",
                "Advertising & Marketing",
                "Airlines & Airports",
                "Animal Shelters",
                "Apparel & Accessories",
                "Architects & Designers",
                "Auto Parts & Accessories",
                "Auto Repair & Services",
                "Bakeries",
                "Banks & Credit Unions",
                "Bars & Nightclubs",
                "Bookkeeping",
                "Books & Stationery",
                "Cafes",
                "Car Dealerships",
                "Car Rentals",
                "Car Wash & Detailing",
                "Catering Services",
                "Charities & Foundations",
                "Chiropractors",
                "City & County Offices",
                "Cleaning Services",
                "Coffee & Tea Shops",
                "Commercial Real Estate",
                "Community Centers",
                "Concrete & Masonry",
                "Consulting & Coaching",
                "Cruise Lines",
                "Cybersecurity",
                "Debt Counseling",
                "Dentists",
                "DMV & Licensing",
                "Driving Schools",
                "Educational Consultants",
                "Electronics & Gadgets",
                "Electronics Manufacturing",
                "E-commerce Solutions",
                "Environmental Groups",
                "Event Planning",
                "Factories & Production",
                "Film & Television",
                "Financial Planning",
                "Fitness & Gyms",
                "Food Delivery Services",
                "Furniture & Decor",
                "Gaming & Esports",
                "General Contractors",
                "Grocery Stores",
                "Graphic Design & Branding",
                "Gyms & Fitness Centers",
                "Handyman Services",
                "Home & Garden",
                "Home Builders",
                "Home Inspectors",
                "Home Staging",
                "Hospitals & Clinics",
                "Hotels & Resorts",
                "HVAC Services",
                "Humanitarian Services",
                "Insurance Providers",
                "Interior Design",
                "Investment Services",
                "IT Consulting",
                "IT Services & Support",
                "Jewelry & Watches",
                "Language Schools",
                "Landscaping & Gardening",
                "Legal Services",
                "Libraries",
                "Machinery & Equipment",
                "Martial Arts",
                "Massage Therapy",
                "Mental Health Services",
                "Mobile App Development",
                "Mortgage Brokers",
                "Moving & Storage Services",
                "Music & Bands",
                "Music Lessons",
                "Online Courses",
                "Painting & Drywall",
                "Performing Arts",
                "Personal Trainers",
                "Pet Boarding",
                "Pet Grooming",
                "Pet Stores",
                "Pet Training",
                "Pharmacies",
                "Photography & Videography",
                "Physical Therapy",
                "Plumbing",
                "Police & Fire Departments",
                "Postal Services",
                "Printing & Publishing",
                "Property Management",
                "Real Estate Agents",
                "Religious Organizations",
                "Restaurants & Cafes",
                "Roofing",
                "Roofing Contractors",
                "Schools & Universities",
                "Social Services",
                "Software Solutions",
                "Specialty Foods",
                "Sporting Goods",
                "Sports Clubs & Teams",
                "Streaming Services",
                "Tax Services",
                "Textile & Apparel",
                "Tour Operators",
                "Towing Services",
                "Toy & Games",
                "Travel Agencies",
                "Tutoring Services",
                "Veterinary Clinics",
                "Virtual Assistants",
                "Vocational & Technical Schools",
                "Web Development",
                "Yoga & Meditation",
                "Yoga & Pilates",
            ];
        @endphp
        <div class="mx-auto container  my-12 px-6 lg:px-8">
          <div class="mx-auto w-full md:w-1/2 lg:mx-0">
            <h2 class="mt-2 text-5xl font-semibold tracking-tight text-gray-900 sm:text-7xl">Search Indianapolis Pros</h2>
            <p class="mt-8 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8"></p>
          </div>
        </div>

        <div class="mx-auto container grid grid-cols-1 md:grid-cols-12 gap-4 my-12 px-6 lg:px-8">
            <div class="col-span-8">
                <flux:input wire:model="search" placeholder="Search for pro..."/>
            </div>
            <div class="col-span-3">
                <flux:select wire:model="categories" variant="listbox" placeholder="Search by category..." multiple>
                    @foreach($businessCategories as $category)
                        <flux:select.option>{{ $category }}</flux:select.option>
                    @endforeach
                </flux:checkbox.group>
            </div>

            <div class="col-span-1 flex justify-end">
                <flux:button wire:click="$refresh" class="w-full" icon="magnifying-glass">Search</flux:button>
            </div>
        </div>
        <div class="mx-auto container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 my-12 px-6 lg:px-8 gap-12">
            
            @forelse($this->listings as $listing)
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

            @empty
         

            @endforelse
      
            <!-- More posts... -->
          </div>

        </div>
    @endvolt

</x-layouts.public>