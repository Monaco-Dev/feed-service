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

        $data = Arr::only($data, [
            'id',
            'user_id',
            'content',
            'created_at',
            'updated_at',
            'pinned_at',
            'shares_count'
        ]);

        Arr::set($data, 'user', new UserResource($this->whenLoaded('user')));

        return $data;
    }
}
