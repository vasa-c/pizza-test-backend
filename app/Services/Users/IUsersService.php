<?php

declare(strict_types=1);

namespace App\Services\Users;

use App\User;

interface IUsersService
{
    /**
     * @param string $password
     * @return string
     */
    public function passwordHash(string $password): string;

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function passwordValidate(string $password, string $hash): bool;

    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email): ?User;

    /**
     * @return string
     */
    public function generateRandomPassword(): string;
}
