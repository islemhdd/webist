<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OfficerController extends Controller
{
    /**
     * Display a listing of all officers.
     */
    public function index()
    {
        $officers = User::with('role')
            ->whereHas('role', function ($query) {
                $query->whereIn('name', [
                    'Chef de compagnie',
                    'Chef de batallaint',
                    'Chef de brigade',
                    'Chef division',
                    'Directeur général',
                    'Medecin'
                ]);
            })
            ->latest()
            ->get();

        return response()->json($officers);
    }

    /**
     * Get all available roles for officers.
     */
    public function roles()
    {
        $roles = Role::whereIn('name', [
            'Chef de compagnie',
            'Chef de batallaint',
            'Chef de brigade',
            'Chef division',
            'Directeur général',
            'Medecin'
        ])->get();

        return response()->json($roles);
    }

    /**
     * Store a newly created officer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['required', 'exists:roles,id'],
            'bat' => ['nullable', 'in:0,1,2,3'],
        ]);

        // Don't hash here - the User model setPasswordAttribute will hash it
        $officer = User::create([
            'username' => $validated['username'],
            'password' => $validated['password'],
            'phone' => $validated['phone'] ?? null,
            'role_id' => $validated['role_id'],
            'bat' => $validated['bat'] ?? '0',
        ]);

        return response()->json([
            'message' => 'Officier créé avec succès.',
            'officer' => $officer->load('role')
        ], 201);
    }

    /**
     * Display the specified officer.
     */
    public function show(string $id)
    {
        $officer = User::with('role')->findOrFail($id);
        return response()->json($officer);
    }

    /**
     * Update the specified officer in storage.
     */
    public function update(Request $request, string $id)
    {
        $officer = User::findOrFail($id);

        $validated = $request->validate([
            'username' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users')->ignore($officer->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_id' => ['sometimes', 'required', 'exists:roles,id'],
            'bat' => ['nullable', 'in:0,1,2,3'],
        ]);

        // Build update data without password
        $updateData = collect($validated)->except('password')->toArray();

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = $validated['password'];
        }

        $officer->update($updateData);

        return response()->json([
            'message' => 'Officier mis à jour avec succès.',
            'officer' => $officer->load('role')
        ]);
    }

    /**
     * Remove the specified officer from storage.
     */
    public function destroy(string $id)
    {
        $officer = User::findOrFail($id);
        $officer->delete();

        return response()->json(['message' => 'Officier supprimé avec succès.'], 200);
    }
}
