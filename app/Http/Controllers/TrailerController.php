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

    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string',
        'description' => 'required|string',
        'type' => 'required|string',
        'features' => 'required|string',
        'size' => 'required|integer',
        'capacity' => 'required|integer',
        'available' => 'required|boolean',
        'price' => 'required|numeric',
        'images' => 'required|array',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048', // Validate each image
    ]);

    // Store images
    $imagePaths = [];
    foreach ($request->file('images') as $image) {
        $path = $image->store('car_images', 'public');
        $imagePaths[] = $path;
    }

    // Convert the array of images to JSON before saving
    $validated['images'] = json_encode($imagePaths);
    $validated['approval_status'] = 'pending';

    Car::create($validated);

    return response()->json(['message' => 'Car added successfully']);
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
        $trailer = Trailer::findOrFail($id); // Will throw a 404 if not found
        return response()->json($trailer);
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
