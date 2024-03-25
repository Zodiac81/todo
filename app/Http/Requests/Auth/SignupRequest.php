<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;

class SignupRequest extends AuthBaseRequest
{
    /**
     * @return array|ValidationRule[]|\mixed[][]|string[]
     */
    public function rules(): array
    {
        return array_merge(parent::rules(),  [
            'name'      => 'required|max:255|string',
            'email'     => 'required|email|unique:users',
        ]);
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return array_merge(parent::messages(),  [
            'name.required'     => 'The name field is required',
            'name.max'          => 'The name field must not be more than 255 characters',
            'name.string'       => 'The name field must be a string',
            'email.required'    => 'The email field is required.',
            'email.email'      => 'The email field must be an email',
            'email.unique'      => 'The email field must be a unique',
        ]);
    }
}
