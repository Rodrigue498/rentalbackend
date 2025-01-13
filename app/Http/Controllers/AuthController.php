<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'phone' => 'required|string|max:11',
        'address' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string|in:renter,owner,administrator',
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'role' => $request->role,
        ]);
        // $user->sendEmailVerificationNotification();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to register user', 'error' => $e->getMessage()], 500);
    }

    return response()->json(['message' => 'Registration successful. Please verify your email.'], 201);
}

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Skip email verification for API testing
        if (!$user->email_verified_at) {
            // Comment out this block to ignore email verification
            // return response()->json(['message' => 'Please verify your email address'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function socialLogin($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();

        $authUser = User::updateOrCreate(
            ['email' => $user->getEmail()],
            ['name' => $user->getName(), 'provider_id' => $user->getId()]
        );

        $token = $authUser->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }
}
