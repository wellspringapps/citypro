<?php

namespace App\Models;

use App\Models\Traits\HasPublicID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasPublicId, SoftDeletes;

    protected $fillable = [
        'listing_id',
        'user_id',
        'name',
        'description',
        'start',
        'end',
        'image'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];


    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
