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

        $fields = [
            'id',
            'uuid',
            'user_id',
            'content',
            'created_at',
            'updated_at',
            'deleted_at',
            'pinned_at',
            'is_shared',
            'is_edited'
        ];

        if (
            Arr::get($data, 'user_id') == optional(request()->user())->id &&
            !Arr::get($data, 'is_shared')
        ) {
            $fields = array_merge($fields, [
                'matches_count'
            ]);
        }

        if (!Arr::get($data, 'is_shared')) {
            $fields = array_merge($fields, [
                'shares_count'
            ]);
        }

        $data = Arr::only($data, $fields);

        Arr::set($data, 'user', new UserResource($this->whenLoaded('user')));
        Arr::set($data, 'shared_post', new PostResource($this->whenLoaded('sharedPost')));

        return $data;
    }
}
