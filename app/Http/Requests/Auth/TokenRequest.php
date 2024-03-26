<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;

class TokenRequest extends AuthBaseRequest
{
    /**
     * @return array|ValidationRule[]|\mixed[][]|string[]
     */
    public function rules(): array
    {
        return array_merge(parent::rules(),  [
            'email'     => 'required|email',
        ]);
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return array_merge(parent::messages(),  [
            'email.max'   => 'The email field must not be more than 255 characters',
            'email.email' => 'The email field must be an email'
        ]);
    }
}
