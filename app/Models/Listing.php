<?php

namespace App\Models;
use Illuminate\Support\Str;
use App\Models\Traits\HasPublicID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use HasFactory, HasPublicID, SoftDeletes;

    protected $fillable = [
        'user_id',
        'header_photo',
        'listing_photo',
        'categories',
        'rating',
        'title',
        'description',
        'website',
        'address',
        'areas_served',
        'hours',
        'phone',
        'email',
        'socials',
        'media',
        'attachments',
        'notes',
        'pro'
    ];

    protected $casts = [
        'hours' => 'array',
        'categories' => 'array',
        'socials' => 'array',
        'media' => 'array',
        'attachments' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function contactSubmissions()
    {
        return $this->hasMany(ContactSubmission::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getRouteKeyName()
    {
        // You'd need to populate this!
        // Probably using an observer.
        return 'public_id';
    }
 
    public function getRouteKey()
    {
        return Str::slug($this->title) . '-' . $this->getAttribute($this->getRouteKeyName());
    }
 
    public function resolveRouteBinding($value, $field = null)
    {
        $id = last(explode('-', $value));
        $model = parent::resolveRouteBinding($id, $field);
 
        if (!$model || $model->getRouteKey() === $value) {
            return $model;
        }
 
        throw new HttpResponseException(
            redirect()->route('public.listing', ['listing' => $model])
        );
    }
}
