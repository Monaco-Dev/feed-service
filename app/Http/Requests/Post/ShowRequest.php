<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

use Facades\App\Repositories\Contracts\PostRepositoryInterface as PostRepository;

class ShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = PostRepository::find($this->id, false);

        if (!$post) abort(404, 'Not found.');

        if (
            !$post->user->is_email_verified ||
            !$post->user->brokerLicense ||
            !($post->user->brokerLicense && $post->user->brokerLicense->is_license_verified)
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
