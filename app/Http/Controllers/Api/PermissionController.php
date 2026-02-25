<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    /**
     * GET /permissions
     */
    public function index()
    {
        Gate::authorize('manage_users');
        return PermissionResource::collection(Permission::all());
    }
}
