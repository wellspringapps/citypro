<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Mail\LoginLink;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class SendMagicLink
{
    public function handle(string $email): void
    {
    

        Mail::to($email)->send(new LoginLink( URL::temporarySignedRoute(
                'login.store', 
                now()->addHour(), 
                ['email' => $email])
            )
        );
    }
}