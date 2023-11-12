<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        // get all roles
        $roles = Role::all();
        // return in json
        return response()->json($roles);
    }
}
