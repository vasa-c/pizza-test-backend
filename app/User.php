<?php

declare(strict_types=1);

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $email
 * @property bool $is_admin
 * @property string $name
 * @property string $address
 * @property string $contacts
 * @property string $currency
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * Checks if the user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $hash = ServiceContainer::users()->passwordHash($password);
        $this->setAttribute('password', $hash);
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password): bool
    {
        $hash = $this->getAttribute('password');
        if ($hash === null) {
            return false;
        }
        return ServiceContainer::users()->passwordValidate($password, $hash);
    }

    /**
     * {@inheritdoc}}
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * {@inheritdoc}}
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * {@inheritdoc}}
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
    ];
}
