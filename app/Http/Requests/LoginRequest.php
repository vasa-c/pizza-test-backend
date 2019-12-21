<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $email
 * @property string $password
 */
class LoginRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'email' => 'string|required',
            'password' => 'string|required',
        ];
    }
}
