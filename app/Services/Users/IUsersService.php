<?php

declare(strict_types=1);

namespace App\Services\Users;

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
}
