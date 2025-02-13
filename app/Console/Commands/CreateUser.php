<?php

namespace App\Console\Commands;

use App\Models\{
    User,
    Listing
};

use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user {name} {email} {title}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a user and their listing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email')
        ]);
    
        $listing = $user->listing()->create([
            'title' => $this->argument('title'),
            'pro' => true
        ]);

        $this->info('User and Listing created!');
    }
}
