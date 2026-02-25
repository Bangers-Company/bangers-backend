<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RolesController extends Controller
{
    public function __construct()
    {
        // Admin only for all methods
        $this->middleware(function ($request, $next) {
            Gate::authorize('manage_users');
            return $next($request);
        });
    }

    /**
     * GET /roles
     */
    public function index()
    {
        return RoleResource::collection(Role::with('permissions')->get());
    }

    /**
     * POST /roles
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create($request->only('name', 'description'));

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return $role->load('permissions');
    }

    /**
     * GET /roles/{id}
     */
    public function show($id)
    {
        return new RoleResource(Role::with('permissions')->findOrFail($id));
    }

    /**
     * PUT /roles/{id}
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update($request->only('name', 'description'));

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return $role->load('permissions');
    }

    /**
     * DELETE /roles/{id}
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted']);
    }
}
