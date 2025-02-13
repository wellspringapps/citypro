<?php

namespace App\Models;

use App\Models\Traits\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasPublicId, SoftDeletes;

    protected $fillable = [
        'listing_id',
        'name',
        'email',
        'rating',
        'review',
        'response',
        'show'
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}