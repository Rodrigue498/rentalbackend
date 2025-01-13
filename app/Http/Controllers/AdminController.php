<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bookings;
use App\Models\Trailer;
use App\Models\User;
use App\Models\Document;
use App\Models\Dispute;
use App\Models\Documents;
use App\Models\Setting;
use Illuminate\Http\Request;
use Sabberworm\CSS\Settings;

class AdminController extends Controller
{
    public function analytics()
    {
        $revenue = Bookings::where('status', 'paid')->sum('total_price');
        $bookingsCount = Bookings::count();
        $topTrailers = Trailer::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        return response()->json([
            'revenue' => $revenue,
            'total_bookings' => $bookingsCount,
            'top_trailers' => $topTrailers,
            'user_growth' => $userGrowth,
        ]);
    }

    public function approveDocument(Request $request, $id)
    {
        $document = Documents::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'nullable|string|max:255',
        ]);

        $document->status = $validated['status'];
        $document->feedback = $validated['feedback'] ?? null;
        $document->save();

        return response()->json(['message' => 'Document status updated successfully']);
    }

    public function approveListing(Request $request, $id)
    {
        $listing = Trailer::findOrFail($id);

        $validated = $request->validate([
            'approval_status' => 'required|in:approved,rejected',
            'admin_feedback' => 'nullable|string|max:255',
        ]);

        $listing->approval_status = $validated['approval_status'];
        $listing->admin_feedback = $validated['admin_feedback'];
        $listing->save();

        return response()->json(['message' => 'Listing approval updated successfully']);
    }

    public function manageDispute(Request $request, $id)
    {
        $dispute = Dispute::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:resolved,unresolved',
            'resolution_message' => 'nullable|string|max:255',
        ]);

        $dispute->status = $validated['status'];
        $dispute->resolution_message = $validated['resolution_message'];
        $dispute->save();

        return response()->json(['message' => 'Dispute updated successfully']);
    }

    public function updateSetting(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'required',
        ]);

        $setting = Setting::updateOrCreate(
            ['key' => $validated['key']],
            ['value' => $validated['value']]
        );

        return response()->json(['message' => 'Setting updated successfully', 'setting' => $setting]);
    }
}
