<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('public_id')->unique();
            $table->foreignId('user_id')->constrained();

            $table->string('header_photo')->nullable();
            $table->string('listing_photo')->nullable();
            $table->json('categories')->nullable();
            $table->double('rating')->default(0);
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('website')->nullable();
            $table->string('address')->nullable(); //https://www.google.com/maps/dir/?api=1&destination={{url_encode($listing->address)}}
            $table->string('areas_served')->nullable();
            $table->json('hours')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->json('socials')->nullable();

            $table->json('media')->nullable();
            $table->json('attachments')->nullable();
            
            $table->text('notes')->nullable();
            $table->boolean('pro')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
