<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
{
    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        switch ($this->method()) {

            case 'GET':
                $this->rules = [
                    'perPage' => 'integer',
                    'page'    => 'integer'
                ];
                break;

            case 'POST':
                $this->rules = [
                    'title'       => 'required|string|min:3|max:255',
                    'description' => 'string|min:3|max:1000'
                ];
                break;

            case 'PATCH':
                $this->rules = [
                    'title'       => 'present|string|min:3|max:255',
                    'description' => 'present|string|min:3|max:1000'
                ];
                break;

            default:
                break;
        }

        return $this->rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'title.required'     => 'The title field is required',
            'title.present'      => 'The title field must be present in request body',
            'title.max'          => 'The title field must not be more than 255 characters',
            'title.min'          => 'The title field must not be less than 3 characters',
            'title.string'       => 'The title field must be a string',
            'description.string' => 'The description field must be a string',
            'description.max'    => 'The description field must not be more than 1000 characters',
            'description.min'    => 'The description field must not be less than 3 characters',
            'description.present' => 'The description field must be present in request body',
            'perPage.integer'    => 'The perPage field must be an integer',
            'page.integer'       => 'The page field must be an integer',
        ];
    }
}
