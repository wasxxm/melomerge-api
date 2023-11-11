<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PublicJamSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        // Check if the image_uri contains 'https://' at the beginning
        $imageUrl = Str::startsWith($this->image_uri, 'https://')
            ? $this->image_uri
            : ( $this->image_uri ? asset('storage/'.$this->image_uri) : null);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'venue' => $this->venue,
            'genre' => $this->genre?->name,
            'start_time' => $this->start_time,
            'participants_count' => $this->participants_count,
            'image_url' => $imageUrl,
            // Add any other fields you need
        ];
    }
}
