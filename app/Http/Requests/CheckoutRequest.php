<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Price;

/**
 * @property array $pizza
 * @property string $currency
 * @property string $email
 * @property string $address
 * @property string $contacts
 * @property bool $outside
 */
class CheckoutRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'pizza' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        $fail($attribute.' is invalid');
                    }
                    foreach ($value as $k => $v) {
                        if (!is_int($v)) {
                            $fail($attribute.' is invalid');
                        }
                        if (($v < 1) || ($v > 25)) {
                            $fail($attribute.' is invalid');
                        }
                    }
                },
            ],
            'currency' => 'required|'.Price::getCurrencyValidationRule(),
            'email' => 'string|required',
            'address' => 'string|required',
            'contacts' => 'string|required',
            'outside' => 'boolean|required',
        ];
    }
}
