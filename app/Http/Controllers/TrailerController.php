<?php

namespace App\Http\Controllers;

use App\Models\Trailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrailerController extends Controller
{
    public function __construct()
    {
        // Only protect methods that need authentication
        $this->middleware('auth:sanctum')->only([
            'create', 'update', 'destroy', 'approveListing', 'setPricing'
        ]);
        
    }
    

    // Create a new trailer
    public function create(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $imagePaths = [];
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('trailers', 'public');
                $imagePaths[] = $path;
            }
        }
    
        // ✅ Only validation rules here, no data assignment
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'type' => 'required|string',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'size' => 'required|numeric',
            'max_payload' => 'required|integer',
            'price' => 'required|numeric',
            'location' => 'nullable|string',
            
            // Do NOT include 'images' => json_encode(...) here
        ]);
    
        $trailer = Trailer::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'features' => json_encode($validated['features'] ?? []),
            'size' => $validated['size'],
            'max_payload' => $validated['max_payload'],
            'price' => $validated['price'],
            'location' => $validated['location'] ?? null,
            'images' => json_encode($imagePaths),
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
            'features' => json_decode($trailer->features, true), 
            'size' => $trailer->size,
            'trailer_weight' => $trailer->trailer_weight,
            'max_payload' => $trailer->max_payload,
            'connector_type' => $trailer->connector_type,
            'trailer_brakes' => $trailer->trailer_brakes,
            'hitch_ball_size' => $trailer->hitch_ball_size,
            'price_per_day' => [
                'single_day' => $trailer->price,
                'multi_day_discount' => [
                    '2_days' => $trailer->price * 0.95,
                    '7_days' => $trailer->price * 0.90,
                    '30_days' => $trailer->price * 0.80,
                ],
            ],
            'location' => $trailer->location,
            'approval_status' => $trailer->approval_status,
            'images' => json_decode($trailer->images, true),
            'owner' => $trailer->owner ? [
                'id' => $trailer->owner->id,
                'name' => $trailer->owner->name,
                'joined_at' => $trailer->owner->created_at->toDateString(),
                'trailer_count' => $trailer->owner->trailers()->count(),
                'rating' => $trailer->owner->rating ?? null,
            ] : null,
    
            // Check if reviews exist before looping
            'reviews' => $trailer->reviews->isNotEmpty() ? $trailer->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'reviewer' => $review->user->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->toDateString(),
                ];
            }) : [],
    
            'average_rating' => $trailer->reviews->isNotEmpty() ? $trailer->reviews->avg('rating') : null,
        ]);
    }
    

   
   // Update method
   public function update(Request $request, $id)
   {
       $trailer = Trailer::find($id);
   
       if (!$trailer) {
           return response()->json(['message' => 'Trailer not found'], 404);
       }
   
       // Check that the logged-in user is the owner
       if ($trailer->user_id !== Auth::id()) {
           return response()->json(['message' => 'Unauthorized'], 403);
       }
   
       $validated = $request->validate([
           'title' => 'required|string',
           'description' => 'required|string',
           'price' => 'required|numeric'
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
   
       // Ensure the user is the owner of the trailer
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
    
        $imagePaths = []; // Initialize the image paths array
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('trailers', 'public');
                $imagePaths[] = $path;
            }
    
            $trailer->images = json_encode($imagePaths); // Update images field
        }
    
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
