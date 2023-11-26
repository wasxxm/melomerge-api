<?php

namespace App\Http\Resources;

use App\Models\JamParticipant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $isParticipant = null;
        if (Auth::check()) {
            $userId = Auth::id();

            // Check if the current user is a participant of the jam session using JamParticipant model
            $isParticipant = JamParticipant::where('jam_session_id', $this->id)
                ->where('user_id', $userId)
                ->exists();
        }

        $distance = $this->distance;
        if ($distance) {
            $distance_miles = $distance * 0.621371; // Convert kilometers to miles

            if ($distance_miles >= 1) {
                $distance_miles = round($distance_miles, 2); // Round to 2 decimal places for miles
                // Check for singular or plural form of "mile"
                $distance_text = $distance_miles == 1 ? "mile away" : "miles away";
                $distance = $distance_miles . " " . $distance_text;
            } else {
                $distance_feet = $distance * 3280.84; // Convert kilometers to feet
                $distance_feet = round($distance_feet); // Round to nearest foot
                // Check for singular or plural form of "foot"
                $distance_text = $distance_feet == 1 ? "foot away" : "feet away";
                $distance = $distance_feet . " " . $distance_text;
            }
        }


        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'venue' => $this->venue,
            'genre' => $this->genre?->name,
            'jam_type' => $this->jamType?->name,
            'start_time' => $this->start_time,
            'participants_count' => $this->participants_count,
            'image_url' => $imageUrl,
            'is_participant' => $isParticipant,
            'distance' => $distance,
            // Add any other fields you need
        ];
    }
}
