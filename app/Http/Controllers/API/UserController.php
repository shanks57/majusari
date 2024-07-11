<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{

     public function index()
    {
        $users = User::with('roles')->paginate();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::with('roles')->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|unique:users',
                'name' => 'required|string',
                'password' => 'required|string|min:6',
                'role' => 'required|string|in:superadmin,admin',
            ]);

            $user = User::create([
                'id' => Str::uuid(),
                'username' => $request->username,
                'name' => $request->name,
                'phone' => $request->phone,
                'debt_receipt' => $request->debt_receipt ?? 0,
                'address' => $request->address,
                'status' => $request->status ?? true,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->role);

            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);

        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'username' => 'string|unique:users,username,' . $id,
            'name' => 'string',
            'password' => 'string|min:6|nullable',
            'role' => 'string|in:superadmin,admin|nullable',
        ]);

        $user->username = $request->username ?? $user->username;
        $user->name = $request->name ?? $user->name;
        $user->phone = $request->phone ?? $user->phone;
        $user->debt_receipt = $request->debt_receipt ?? $user->debt_receipt;
        $user->address = $request->address ?? $user->address;
        $user->status = $request->status ?? $user->status;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($request->role) {
            $user->syncRoles([$request->role]);
        }

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
}
