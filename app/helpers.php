<?php
use Illuminate\Support\Carbon;

if(!function_exists('generateNanoId')){
    function generateNanoId(int $len = 21, mixed $entropy = 'balanced', string $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        $id = '';
        $alphabet_len = strlen($alphabet);

        if ($entropy === false || $entropy == 'balanced') {
            $tolen = $entropy == 'balanced' ? $len / 2 : $len;
            for ($i = 0; $i < $tolen; $i++) {
                $id .= $alphabet[random_int(0, $alphabet_len - 1)];
            }
            if ($entropy != 'balanced') {
                return $id;
            }
        }

        $mask = (2 << (int) (log($alphabet_len - 1) / M_LN2)) - 1;
        $step = (int) ceil(1.6 * $mask * $len / $alphabet_len);

        while (true) {
            $bytes = unpack('C*', random_bytes($len));
            for ($i = 1; $i <= $step; $i++) {
                $byte = $bytes[$i]&$mask;

                if (isset($alphabet[$byte])) {
                    $id .= $alphabet[$byte];

                    if (strlen($id) === $len) {
                        return $id;
                    }
                }
            }
        }
    }
}

if(!function_exists('isImage')){
    function isImage($file): bool{
        try{
            $image = getimagesize($file);

            if($image === false){
                return false;
            }

            
            $imageMime = $image['mime'];
            $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff', 'image/webp', 'image/svg+xml'];

            return in_array($imageMime, $allowedMime);
        } catch(Exception $e) {
            return true;
        }
    }
}

function formatHumanReadableDateRange(Carbon $start, Carbon $end): string {

    // Format date and time
    $dateFormat = 'M j, Y';
    $timeFormat = 'g:i A';

    if ($start->toDateString() === $end->toDateString()) {
        // Same day: Show date once with time range
        return $start->format("$dateFormat g:i") . ' - ' . $end->format($timeFormat);
    } else {
        // Different days: Show minimal full range
        return $start->format("$dateFormat g:i A") . ' - ' . $end->format('M j g:i A');
    }
}

function limitString($string, $limit = 155) {
    // Strip HTML tags
    $cleanString = strip_tags($string);
    
    // Trim to 152 characters if over 155 and append "..."
    if (strlen($cleanString) > $limit) {
        return substr($cleanString, 0, $limit - 3) . "...";
    }
    
    return $cleanString;
}



function getBusinessCategories (){
    return [
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
}