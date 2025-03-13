<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:11',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:renter,owner,administrator',
            'google_id' => 'nullable|string|max:255'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'phone' => $request->phone,
                'role' => $request->role,
                'google_id' => $request->google_id ?? null
            ]);
    
            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $token // 🔥 Include the token in the response
            ], 201);
    
        } catch (\Exception $e) {
            \Log::error('User Registration Failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to register user'], 500);
        }
    }

   



    public function registerGoogle(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'google_id' => 'required|string',
        ]);
    
        try {
            // Check if user exists
            $user = User::where('email', $request->email)->orWhere('google_id', $request->google_id)->first();
    
            if (!$user) {
                // Create new user if not found
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'google_id' => $request->google_id,
                ]);
            }
    
            // Generate token for authentication
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to process request', 'error' => $e->getMessage()], 500);
        }
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

        return response()->json(['access_token' => $token, 'token_type' => 'Bearer','user' => $user]);
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
