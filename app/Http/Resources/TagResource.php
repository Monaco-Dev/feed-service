<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TagResource extends JsonResource
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
            'name',
            'taggables_count'
        ];

        $data = Arr::only($data, $fields);

        Arr::set($data, 'name', $data['name']['en']);

        return $data;
    }
}
