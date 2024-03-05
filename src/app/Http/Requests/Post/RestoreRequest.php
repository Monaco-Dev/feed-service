<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class RestoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('restore-post', $this->trashed_post);
    }
}
