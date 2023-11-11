<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateJamSessionRequest;
use App\Http\Resources\PublicJamSessionResource;
use App\Models\JamSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JamSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection | JsonResponse
    {
        // Start a query builder for JamSession
        $query = JamSession::query();

        // Assuming 'is_public' is a boolean field in your jam_sessions table
        $query->where('is_public', true);

        // Filter by date if provided
        if ($request->has('start_date')) {
            $query->whereDate('start_date', $request->date);
        }

        // Filter by genre_id if provided
        if ($request->has('genre_id')) {
            $query->where('genre_id', $request->genre_id);
        }

        $query->orderBy('created_at', 'desc');

        // Add pagination
        $jamSessions = $query->paginate(15); // You can customize the number per page

        // Wrap the collection of jam sessions with the resource
        return PublicJamSessionResource::collection($jamSessions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateJamSessionRequest $request
     * @return PublicJamSessionResource
     */
    public function store(CreateJamSessionRequest $request): PublicJamSessionResource {

        // Create a new JamSession
        $jamSession = new JamSession();

        // Set the attributes
        $jamSession->organizer_id = $request->user()->id;
        $jamSession->name = $request->name;
        $jamSession->start_time = $request->start_date;
//        $jamSession->end_date = $request->end_date;
        $jamSession->genre_id = $request->genre_id;
        $jamSession->is_public = !$request->is_private;
        $jamSession->description = $request->description;
        $jamSession->venue = $request->location;

        // save the image
        if ($request->hasFile('image')) {
            // Store the image in the 'public' disk (usually 'storage/app/public')
            // and generate a unique filename for it
            $path = $request->file('image')->store('jam_sessions', 'public');

            // Store the path in the database
            $jamSession->image_uri = $path;
        }


        // Save the JamSession
        $jamSession->save();

        // Return the resource
        return new PublicJamSessionResource($jamSession);
    }
}
