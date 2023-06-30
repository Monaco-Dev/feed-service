<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $user = Arr::get($data, 'user');
        $shares = Arr::get($data, 'shares');

        $shares = collect($shares)->map(function ($share) {
            return [
                'id' => Arr::get($share, 'id'),
                'first_name' => Arr::get($share, 'first_name'),
                'last_name' => Arr::get($share, 'last_name'),
                'username' => Arr::get($share, 'username'),
                'email' => Arr::get($share, 'email'),
                'phone_number' => Arr::get($share, 'phone_number'),
                'full_name' => Arr::get($share, 'first_name') . ' ' . Arr::get($share, 'last_name'),
            ];
        })->toArray();

        return [
            'id' => Arr::get($data, 'id'),
            'user_id' => Arr::get($data, 'user_id'),
            'content' => Arr::get($data, 'content'),
            'shares_count' => Arr::get($data, 'shares_count') ?? 0,
            'pinned_at' => Arr::get($data, 'pinned_at'),
            'author' => [
                'id' => Arr::get($user, 'id'),
                'first_name' => Arr::get($user, 'first_name'),
                'last_name' => Arr::get($user, 'last_name'),
                'username' => Arr::get($user, 'username'),
                'email' => Arr::get($user, 'email'),
                'phone_number' => Arr::get($user, 'phone_number'),
                'full_name' => Arr::get($user, 'first_name') . ' ' . Arr::get($user, 'last_name'),
            ],
            'shares' => $shares
        ];
    }
}
