<?php

namespace App\Models;

use App\Models\Traits\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactSubmission extends Model
{
    use HasPublicId, SoftDeletes;

    protected $fillable = [
        'listing_id',
        'name',
        'email',
        'message'
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
