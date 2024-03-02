<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $fields = [
            'id',
            'uuid',
            'full_name',
            'first_name',
            'last_name',
            'avatar_url',
            'is_verified'
        ];

        if (Arr::get($data, 'id') != optional(request()->user())->id) {
            $fields = array_merge($fields, [
                'is_incoming_invite',
                'is_outgoing_invite',
                'is_following',
                'is_follower',
                'is_connection',
            ]);
        }

        $data = Arr::only($data, $fields);

        return $data;
    }
}
