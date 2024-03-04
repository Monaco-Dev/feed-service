<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => [
                'required',
                'string'
            ],
            'type' => [
                'required',
                'string',
                Rule::in(config('constants.post.types'))
            ],
            'tags' => [
                'nullable',
                'array'
            ],
            'tags.*' => [
                'string'
            ]
        ];
    }
}
