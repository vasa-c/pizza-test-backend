<?php

declare(strict_types=1);

namespace App\Services\Users;

use App\User;
use Illuminate\Support\Facades\Hash;
use Exception;

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

    /**
     * {@inheritdoc}
     */
    public function generateRandomPassword(): string
    {
        $diaps = [
            [48, 57],
            [65, 90],
            [97, 122]
        ];
        $chars = [];
        for ($i = 0; $i < 10; $i++) {
            try {
                [$min, $max] = $diaps[random_int(0, 2)];
                $chars[] = chr(random_int($min, $max));
            } catch (Exception $e) {
                [$min, $max] = $diaps[mt_rand(0, 2)];
                $chars[] = chr(mt_rand($min, $max));
            }
        }
        return implode('', $chars);
    }
}
