<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\Role;
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
}
