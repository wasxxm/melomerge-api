<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Instrument;
use App\Models\JamType;
use App\Models\Role;
use App\Models\SkillLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * Get all roles and instruments
     *
     * @return JsonResponse
     */
    public function roles_and_instruments(): JsonResponse
    {
        try {
            $roles = Role::all();
            $instruments = Instrument::all();

            return response()->json([
                'success' => true,
                'data' => [
                    'roles' => $roles,
                    'instruments' => $instruments,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving roles and instruments',
                'errors' => ['server' => [$e->getMessage()]]
            ], 500);
        }
    }

    /**
     * * Get all the data required to create a jam session
     */
    public function get_jam_session_create_data(): JsonResponse
    {
        try {
            $genres = Genre::all();
            $jam_types = JamType::all();

            return response()->json([
                'success' => true,
                'data' => [
                    'genres' => $genres,
                    'jam_types' => $jam_types,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving data for creating a jam session',
                'errors' => ['server' => [$e->getMessage()]]
            ], 500);
        }
    }
}
