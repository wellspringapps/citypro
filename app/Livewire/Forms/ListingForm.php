<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ListingForm extends Form
{
    protected $listing;

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

        if(!$listing->pro){
            $data['description'] = strip_tags($data['description'], ['<p>', '<h1>', '<h2>', '<h3>', '<strong>', '<em>', '<s>', '<ul>', '<li>', '<ol>', '<blockquote>']);
            $this->description = $data['description'];
        }
        
        $data['areas_served'] = $this->areasServed;

        $listing->update($data);
    }

    public function saveHeaderPhoto($listing)
    {
        $listing->update([
            'header_photo' => $this->headerPhoto
        ]);
    }

    public function saveListingPhoto($listing)
    {
        $listing->update([
            'listing_photo' => $this->listingPhoto
        ]);
    }


    public function getPossibleCategories()
    {
        return getBusinessCategories();
    }
}
