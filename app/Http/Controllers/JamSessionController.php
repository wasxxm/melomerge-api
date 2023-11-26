<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateEntryException;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateJamSessionRequest;
use App\Http\Requests\JoinJamSessionRequest;
use App\Http\Resources\JamSessionDetailsResource;
use App\Http\Resources\PublicJamSessionResource;
use App\Models\JamSession;
use App\Utilities\Geocoder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class JamSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            // Start a query builder for JamSession
            $query = JamSession::query();

            // Assuming 'is_public' is a boolean field in your jam_sessions table
            $query->where('is_public', true);

            // Filter by date if provided and not empty
            if ($request->has('start_date') && $request->start_date) {
                $query->whereDate('start_time', '>=', $request->start_date);
            }

            // Filter by genre_id if provided
            if ($request->has('genre_id') && $request->genre_id) {
                $query->where('genre_id', $request->genre_id);
            }

            // Filter by name if provided
            if ($request->has('name') && $request->name) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            // Filter by venue if provided
            if ($request->has('venue') && $request->venue) {
                $coordinates = Geocoder::geocodeAddress($request->venue);

                if ($coordinates) {
                    $latitude = $coordinates['lat'];
                    $longitude = $coordinates['lng'];
                    $maxDistance = 10; // Maximum distance in kilometers

                    // Use the scope for nearby jams
                    $query->nearby($latitude, $longitude, $maxDistance);
                }
            }

            // filter by location if provided
            if ($request->has('location') && $request->location) {
                $location = explode(',', $request->location);
                $latitude = $location[0];
                $longitude = $location[1];

                if ($latitude != 0 && $longitude != 0) {
                    $maxDistance = 10; // Maximum distance in kilometers

                    // Use the scope for nearby jams
                    $query->nearby($latitude, $longitude, $maxDistance);
                }
            }

            // Filter by organizer if provided
            if ($request->has('organizer') && $request->organizer) {
                $query->whereHas('organizer', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->organizer . '%');
                });
            }

            // Filter by participant if provided
            if ($request->has('participant') && $request->participant) {
                $query->whereHas('participants', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->participant . '%');
                });
            }

            // Filter by instrument if provided
            if ($request->has('instrument') && $request->instrument) {
                $query->whereHas('participants', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->instrument . '%');
                });
            }

            // Filter by role if provided
            if ($request->has('role') && $request->role) {
                $query->whereHas('participants', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->role . '%');
                });
            }

            // show only the jams created by this user
            if ($request->has('created-jams') && $request->get('created-jams')) {
                $query->where('organizer_id', $request->user()->id);
            }

            // show only the jams joined by this user
            if ($request->has('joined-jams') && $request->get('joined-jams')) {
                $query->whereHas('participants', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                });
            }


//            $query->orderBy('start_time', 'desc');
            $query->orderBy('created_at', 'desc');

            // Add pagination
            $jamSessions = $query->paginate(15);

            // Wrap the collection of jam sessions with the resource
            return PublicJamSessionResource::collection($jamSessions);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving the jam sessions',
                'errors' => ['server' => [$e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateJamSessionRequest $request
     * @return PublicJamSessionResource|JsonResponse
     */
    public function store(CreateJamSessionRequest $request): PublicJamSessionResource|JsonResponse
    {
        try {
            // Create a new JamSession
            $jamSession = new JamSession();

            // Set the attributes
            $jamSession->organizer_id = $request->user()->id;
            $jamSession->name = $request->name;
            $jamSession->start_time = $request->start_date;
//        $jamSession->end_date = $request->end_date;
            $jamSession->genre_id = $request->genre_id;
            $jamSession->jam_type_id = $request->jam_type_id;
            $jamSession->is_public = !$request->is_private;
            $jamSession->description = $request->description;
            $jamSession->venue = $request->location;

            $coordinates = Geocoder::geocodeAddress($request->location);
            if ($coordinates) {
                $jamSession->location = DB::raw("ST_GeomFromText('POINT({$coordinates['lng']} {$coordinates['lat']})')");
            }


            // save the image
            if ($request->hasFile('image')) {
                // Store the image in the 'public' disk (usually 'storage/app/public')
                // and generate a unique filename for it
                $path = $request->file('image')->store('jam_sessions', 'public');

                // Store the path in the database
                $jamSession->image_uri = $path;
            }


            // Save the JamSession
            if (!$jamSession->save()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating the jam session',
//                    'errors' => ['create' => ['Failed to create the jam session.']]
                ], 500);
            }

            // Return the resource
            return new PublicJamSessionResource($jamSession);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating the jam session',
                'errors' => ['server' => [$e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param JamSession $jamSession
     * @return JamSessionDetailsResource
     */
    public function show(JamSession $jamSession): JamSessionDetailsResource
    {
        $jamSession->load('participants.user', 'participants.role', 'participants.instrument', 'participants.skill_level');

        return new JamSessionDetailsResource($jamSession);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param CreateJamSessionRequest $request
     * @param JamSession $jamSession
     * @return PublicJamSessionResource|JsonResponse
     */
    public function update(CreateJamSessionRequest $request, JamSession $jamSession): PublicJamSessionResource|JsonResponse
    {
        try {
            // Set the attributes
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
            if (!$jamSession->save()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating the jam session',
//                    'errors' => ['update' => ['Failed to update the jam session.']]
                ], 500);
            }

            // Return the resource
            return new PublicJamSessionResource($jamSession);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating the jam session',
                'errors' => ['server' => [$e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param JamSession $jamSession
     * @return JsonResponse
     */
    public function destroy(JamSession $jamSession): JsonResponse
    {
        try {
            $deleted = $jamSession->delete();

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting the jam session',
//                    'errors' => ['delete' => ['Failed to delete the jam session.']]
                ], 500);
            }

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting the jam session',
                'errors' => ['server' => [$e->getMessage()]]
            ], 500);
        }
    }


    /**
     * Join the specified resource from storage.
     *
     * @param JamSession $jamSession
     * @return JsonResponse
     * @throws DuplicateEntryException
     * @throws GeneralException
     */
    public function join(JamSession $jamSession, JoinJamSessionRequest $request): JsonResponse
    {
        try {
            $userId = auth()->user()->id;
            $roleId = $request->validated('role_id');  // Retrieve role_id from the request
            $skillLevelId = $request->validated('skill_level_id'); // Retrieve skill_level_id from the request
            $message = $request->validated('message'); // Retrieve message from the request
            $instrumentId = $request->validated('instrument_id');

            // Add the authenticated user to the jam session with additional data
            $jamSession->participants()->attach($userId, [
                'role_id' => $roleId,
                'instrument_id' => $instrumentId <= 0 ? null : $instrumentId,
                'skill_level_id' => $skillLevelId <= 0 ? null : $skillLevelId,
                'message' => $message
            ]);

            // Check if the user is now a participant
            $isParticipant = $jamSession->participants()
                ->where('user_id', $userId)
                ->exists();

            if (!$isParticipant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error joining the jam session',
//                    'errors' => ['join' => ['Failed to add the user to the jam session.']]
                ], 500);
            }

            // Return a 204 No Content response on success
            return response()->json(null, 204);

        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                // Check for a known substring in the exception message that indicates a duplicate entry
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    throw new DuplicateEntryException('You are already a participant');
                }

                // Other database related errors
                throw new GeneralException('Database error occurred', 'A database error occurred. Please try again.' . $e->getMessage());
            }

            throw new GeneralException('Error joining the jam session', $e->getMessage());
        }
    }

}
