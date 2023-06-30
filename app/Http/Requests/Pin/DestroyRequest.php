<?php

namespace App\Http\Requests\Pin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Facades\App\Repositories\Contracts\PinRepositoryInterface as PinRepository;

class DestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $pin = PinRepository::find($this->id, false);

        if (!$pin) abort(404, 'Not found.');

        if (Auth::user()->id !== $pin->user_id) return false;

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
