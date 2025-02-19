<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Actions\Auth\SendMagicLink;

class LoginForm extends Form
{
    #[Validate('required|email')]
    public $email = '';

    public function submit()
    {
        $user = User::where('email', $this->email)->first();

        if($user){
            (new SendMagicLink)->handle($this->email);
        }
        
        \Flux::toast('If a user was found a magic link has been sent to your email address. You can close this tab now.');
    }
}
