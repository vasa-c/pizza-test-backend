<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $status
 */
class AdminChangeStatusRequest extends FormRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'status' => 'required|in:delivery,success,fail',
        ];
    }
}
