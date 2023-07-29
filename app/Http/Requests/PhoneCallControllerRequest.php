<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read int $user_id
 */
class PhoneCallControllerRequest extends FormRequest
{
    /**
     * @return array<string, array<string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer'
            ]
        ];
    }
}
