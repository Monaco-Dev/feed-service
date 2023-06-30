<?php

namespace App\Http\Requests\Share;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Facades\App\Repositories\Contracts\ShareRepositoryInterface as ShareRepository;

class DestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $share = ShareRepository::find($this->id, false);

        if (!$share) abort(404, 'Not found.');

        if (Auth::user()->id !== $share->user_id) return false;

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
