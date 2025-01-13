<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Fetch all users
    public function index()
    {
        return User::all();
    }

    // Create a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    // Fetch a single user
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        }
        return response()->json(['message' => 'User not found'], 404);
    }
    public function stripeAccount()
{
    return $this->hasOne(StripeAccount::class);
}


    // Update a user
    public function updateProfile(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:15',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Update user details
        $user->update($validatedData);

        return response()->json([
            'message' => 'Profile updated successfully!',
            'user' => $user,
        ]);
    }


    // Delete a user
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }
}
