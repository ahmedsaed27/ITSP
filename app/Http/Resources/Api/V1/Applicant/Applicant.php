<?php

namespace App\Http\Resources\Api\V1\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Applicant extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'apply' => $this->applies,
            'area' => $this->area,
            'birthYear' => $this->birthYear,
            'area' => $this->area,
            'images' => collect($this->images)->map(fn($image) => asset('assets/files/' . $image))->toArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
