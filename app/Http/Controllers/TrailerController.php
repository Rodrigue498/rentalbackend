<?php

namespace App\Http\Controllers;

use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrailerController extends Controller
{
    public function __construct()
    {
        // Ensure the user is authenticated for CRUD operations
        $this->middleware('auth:sanctum');
    }

    // Create a new trailer
    public function create(Request $request)
{
    if (auth()->user()->role !== 'owner' && auth()->user()->role !== 'administrator') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Validate the incoming request data
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string',
        'description' => 'required|string',
        'type' => 'required|string',
        'features' => 'nullable|array', // Store as JSON instead of string
        'features.*' => 'string', // Each feature should be a string
        'size' => 'required|numeric', // Updated to decimal instead of integer
        'max_load' => 'required|integer', // Updated column name
        'available' => 'required|boolean',
        'price' => 'required|numeric',
        'location' => 'nullable|string', // If we added a location column
        'images' => 'required|array',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048', // Validate each image
    ]);

    // Handle image upload
    $imagePaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('trailers', 'public'); // Store in the 'public/trailers' folder
            $imagePaths[] = $path;
        }
    }

    // Create a new Trailer
    $trailer = \App\Models\Trailer::create([
        'user_id' => $validated['user_id'],
        'title' => $validated['title'],
        'description' => $validated['description'],
        'type' => $validated['type'],
        'features' => json_encode($validated['features'] ?? []), // Store features as JSON
        'size' => $validated['size'], 
        'max_load' => $validated['max_load'], // Use new column name
        'available' => $validated['available'],
        'price' => $validated['price'],
        'location' => $validated['location'] ?? null, // If location exists
        'images' => json_encode($imagePaths), // Store image paths as JSON
    ]);

    return response()->json(['message' => 'Trailer created successfully', 'trailer' => $trailer], 201);
}


    // List all trailers
    public function list(Request $request)
    {
        $query = Trailer::query();
    
        // Show only approved trailers for non-admin users
       
    
        // Apply filters if provided in the request
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
    
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }
    
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }
    
        if ($request->has('features')) {
            $query->where('features', 'like', '%' . $request->input('features') . '%');
        }
    
        if ($request->has('size_min')) {
            $query->where('size', '>=', $request->input('size_min'));
        }
    
        if ($request->has('size_max')) {
            $query->where('size', '<=', $request->input('size_max'));
        }
    
        if ($request->has('owner_rating')) {
            $query->whereHas('owner', function ($q) use ($request) {
                $q->where('rating', '>=', $request->input('owner_rating'));
            });
        }
    
        // Paginate results
        $trailers = $query->paginate(10);
    
        return response()->json($trailers);
    }
    


    // View a specific trailer by ID
    public function show($id)
    {
        $trailer = Trailer::with(['owner', 'reviews'])->find($id);
    
        if (!$trailer) {
            return response()->json(['message' => 'Trailer not found'], 404);
        }
    
        return response()->json([
            'id' => $trailer->id,
            'title' => $trailer->title,
            'description' => $trailer->description,
            'type' => $trailer->type,
            'features' => json_decode($trailer->features, true), // Convert JSON to array
            'size' => $trailer->size,
            'trailer_weight' => $trailer->trailer_weight, // Add trailer weight
            'max_payload' => $trailer->max_payload, // Add max payload
            'connector_type' => $trailer->connector_type, // Add connector type
            'trailer_brakes' => $trailer->trailer_brakes, // Add trailer brakes info
            'hitch_ball_size' => $trailer->hitch_ball_size, // Add hitch ball size
            'available' => $trailer->available,
            'price_per_day' => [
                'single_day' => $trailer->price, // Base price for a single day
                'multi_day_discount' => [
                    '2_days' => $trailer->price * 0.95,  // 5% discount for 2 days
                    '7_days' => $trailer->price * 0.90,  // 10% discount for 7 days
                    '30_days' => $trailer->price * 0.80, // 20% discount for 30 days
                ],
            ],
            'location' => $trailer->location,
            'approval_status' => $trailer->approval_status,
            'images' => json_decode($trailer->images, true), // Convert JSON to array
            'owner' => [
                'id' => $trailer->owner->id,
                'name' => $trailer->owner->name,
                'joined_at' => $trailer->owner->created_at->toDateString(), // Host join date
                'trailer_count' => $trailer->owner->trailers->count(), // Count of trailers by host
                'rating' => $trailer->owner->rating ?? null,
            ],
            'reviews' => $trailer->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'reviewer' => $review->user->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->toDateString(),
                ];
            }),
            'average_rating' => $trailer->reviews->avg('rating') ?? null, // Average rating of trailer
        ]);
    }
    public function store(Request $request)
{
    // Validate incoming request
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'type' => 'required|string',
        'features' => 'nullable|array',
        'size' => 'required|string',
        'trailer_weight' => 'required|numeric',
        'max_payload' => 'required|numeric',
        'connector_type' => 'required|string',
        'trailer_brakes' => 'required|string',
        'hitch_ball_size' => 'required|string',
        'available' => 'required|boolean',
        'price' => 'required|numeric',
        'location' => 'required|string',
        'images' => 'nullable|array',
        'images.*' => 'url', // Validate each image URL
    ]);

    // Get the authenticated user
    $user = auth()->user();

    // Create new trailer
    $trailer = Trailer::create([
        'user_id' => $user->id,  // Associate with the logged-in user
        'title' => $request->title,
        'description' => $request->description,
        'type' => $request->type,
        'features' => json_encode($request->features),
        'size' => $request->size,
        'trailer_weight' => $request->trailer_weight,
        'max_payload' => $request->max_payload,
        'connector_type' => $request->connector_type,
        'trailer_brakes' => $request->trailer_brakes,
        'hitch_ball_size' => $request->hitch_ball_size,
        'available' => $request->available,
        'price' => $request->price,
        'location' => $request->location,
        'images' => json_encode($request->images),
        'approval_status' => 'pending', // Set default approval status
    ]);

    return response()->json([
        'message' => 'Trailer added successfully',
        'trailer' => $trailer
    ], 201);
}


    // Update a trailer
   // Update method
public function update(Request $request, $id)
{
    $trailer = Trailer::find($id);

    if (!$trailer) {
        return response()->json(['message' => 'Trailer not found'], 404);
    }

    logger('Auth ID: ' . Auth::id());
    logger('Trailer User ID: ' . $trailer->user_id);



    $validated = $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'available' => 'required|boolean',
    ]);

    $trailer->update($validated);

    return response()->json(['message' => 'Trailer updated successfully', 'data' => $trailer]);
}

    public function destroy($id)
    {
        $trailer = Trailer::find($id);

        if (!$trailer) {
            return response()->json(['message' => 'Trailer not found'], 404);
        }


        if ($trailer->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $trailer->delete();

        return response()->json(['message' => 'Trailer deleted successfully']);
    }

public function approveListing(Request $request, $id)
{

    $trailer = Trailer::findOrFail($id);

    // Ensure the authenticated user is an administrator
    if (auth()->user()->role !== 'administrator') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Validate the approval_status and admin_feedback fields
    $validated = $request->validate([
        'approval_status' => 'required|in:pending,approved,rejected',
        'admin_feedback' => 'nullable|string|max:255',
    ]);

    // Update the trailer's approval status and feedback
    $trailer->approval_status = $validated['approval_status'];
    $trailer->admin_feedback = $validated['admin_feedback'];
    $trailer->save();

    // Return a success response
    return response()->json([
        'message' => 'Trailer approval status updated successfully',
        'data' => $trailer,
    ]);
}

}
