<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;
use App\Actions\Auth\SendMagicLink;

class LoginForm extends Form
{
    #[Validate('required|email')]
    public $email = '';

    public function submit()
    {
        (new SendMagicLink)->handle($this->email);
        \Flux::toast('A magic link has been sent to your email address. You can close this tab now.');
    }
}
