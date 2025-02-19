<?php

namespace App\Actions\Users;

use App\Models\User;

class CreateUserAction
{
    public function handle(string $name, string $email): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
        ]);
    }
}