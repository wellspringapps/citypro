<?php

namespace App\Actions\Users;

use App\Models\User;

class CreateNewUser
{
    public function handle(string $name, string $email): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
        ]);
    }
}