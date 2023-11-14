<?php
// app/Utilities/Geocoder.php

namespace App\Utilities;

use Illuminate\Support\Facades\Http;

class Geocoder
{
    public static function geocodeAddress($address)
    {
        $apiKey = config('services.google_maps.api_key');

        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => $apiKey,
        ]);

        if ($response->ok()) {
            $data = $response->json();

            if (isset($data['results'][0]['geometry']['location'])) {
                $location = $data['results'][0]['geometry']['location'];
                return [
                    'lat' => $location['lat'],
                    'lng' => $location['lng'],
                ];
            }
        }

        return null; // Return null if geocoding fails
    }
}
