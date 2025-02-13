<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ListingForm extends Form
{
    protected $listing;

    protected $businessCategories = [
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

    public $headerPhoto;

    public $listingPhoto = "https://images.unsplash.com/photo-1564758866811-4780aa0a1f49?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80";

    public $title = 'Title';

    public $description = 'A little bit about us...';

    public $hours = [
        'monday' => '-',
        'tuesday' => '-',
        'wednesday' => '-',
        'thursday' => '-',
        'friday' => '-',
        'saturday' => '-',
        'sunday' => '-',
    ];

    public $address = '-';

    public $areasServed = '-';

    public $rating = 0;

    public $numOfReviews = 0;

    public $website = '-';

    public $email = "-";

    public $phone = "-";

    public $media = [];

    public $attachments = [];

    public $categories = [];

    public $socials = [];


    public function setListing($listing)
    {
       
        $this->listing = $listing;

        $this->title = $listing->title;
        $this->description = $listing->description;
        $this->hours = $listing->hours ?? $this->hours;
        $this->address = $listing->address;
        $this->areasServed = $listing->areas_served;
        $this->rating = $listing->rating;
        $this->numOfReviews = $listing->num_of_reviews;
        $this->website = $listing->website;
        $this->email = $listing->email;
        $this->phone = $listing->phone;
        $this->media = $listing->media ?? $this->media;
        $this->attachments = $listing->attachments ?? $this->attachments;
        $this->socials = $listing->socials ?? $this->socials;
        $this->headerPhoto = $listing->header_photo;
        $this->listingPhoto = $listing->listing_photo;
        $this->categories = $listing->categories;
    }

    public function save($listing)
    {
        $data = $this->all();

        $data['header_photo'] = $this->headerPhoto;
        $data['listing_photo'] = $this->listingPhoto;
        $data['areas_served'] = $this->areasServed;

        $listing->update($data);
    }

    public function getPossibleCategories()
    {
        return $this->businessCategories;
    }
}
