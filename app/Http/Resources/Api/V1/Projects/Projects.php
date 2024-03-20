<?php

namespace App\Http\Resources\Api\V1\Projects;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

use Illuminate\Http\JsonResponse;


class Projects extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'images' => collect($this->images)->map(fn($image) => asset('assets/files/' . $image))->toArray(),
            'link' => $this->link,
            'description' => $this->description,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'image' => asset('assets/files/' . $this->category->image),
                'description' => $this->category->description,
                'created_at' => $this->category->created_at,
                'updated_at' => $this->category->updated_at,
            ],
        ];

        
    }
  
}
