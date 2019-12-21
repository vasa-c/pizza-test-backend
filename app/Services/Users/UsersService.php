<?php

declare(strict_types=1);

namespace App\Services\Users;

use App\User;
use Illuminate\Support\Facades\Hash;

class UsersService implements IUsersService
{
    /**
     * {@inheritdoc}
     */
    public function passwordHash(string $password): string
    {
        return Hash::make($password);
    }

    /**
     * {@inheritdoc}
     */
    public function passwordValidate(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }

    /**
     * {@inheritdoc}
     */
    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
