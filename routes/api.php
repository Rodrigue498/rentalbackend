<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\TrailerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\SeasonalPricingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Http;

Route::get('/places', function (Request $request) {
    $input = $request->query('input');

    if (!$input) {
        return response()->json(['error' => 'Input is required'], 400);
    }

    $apiKey = env('GOOGLE_API_KEY');
    $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input={$input}&key={$apiKey}&types=(cities)|establishment";

    $response = Http::get($url);

    if ($response->failed()) {
        return response()->json(['error' => 'Failed to fetch places'], 500);
    }

    return response()->json(['message' => 'API is working'], 200);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/documents/archive', [DocumentController::class, 'archiveDocument']);
    Route::get('/documents', [DocumentController::class, 'listDocuments']);
    Route::get('/documents/{id}/download', [DocumentController::class, 'downloadDocument']);
});


Route::post('/payments/process', [PaymentController::class, 'processPayment'])->middleware('auth:sanctum');
Route::get('/payments',[PaymentController::class,'processPayment'])->middleware('auth:sanctum');
    Route::put('/users/update-profile/{id}', [UserController::class, 'updateProfile']);

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email verified successfully.']);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification email resent.']);
})->middleware(['auth', 'throttle:6,1']);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register-google', [AuthController::class, 'registerGoogle']);
    Route::put('/users/{id}', [UserController::class, 'update']);


Route::post('/password/forgot', [PasswordController::class, 'sendResetLink']);
Route::post('/password/reset', [PasswordController::class, 'resetPassword'])
    ->middleware('auth:sanctum'); // If using Sanctum


Route::post('/social-login/{provider}', [AuthController::class, 'socialLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});



Route::middleware(['auth:sanctum', 'role:renter'])->group(function () {
    Route::get('/trailers', [TrailerController::class, 'list']);
    Route::post('/book', [BookingController::class, 'book']);
    Route::post('/review', [ReviewController::class, 'store']);
    Route::get('/reviews/{trailer_id}', [ReviewController::class, 'index']);
    Route::delete('/book/{id}/cancel', [BookingController::class, 'cancelBooking']);
    Route::get('/trailers/{trailer_id}/unavailable-dates', [BookingController::class, 'getUnavailableDates']);
    Route::get('/trailers/{id}/availability', [AvailabilityController::class, 'getAvailability']);
Route::put('/trailers/{id}/availability', [AvailabilityController::class, 'updateAvailability']);
Route::get('/trailers/{trailer_id}/unavailable-dates', [BookingController::class, 'getUnavailableDates']);
Route::get('/trailers/{id}/availability', [AvailabilityController::class, 'getAvailability']);
});

Route::middleware(['auth:sanctum', 'role:owner'])->group(function () {
    Route::get('/trailers', [TrailerController::class, 'list']);
    Route::post('/trailers/create', [TrailerController::class, 'create']);
    Route::put('/trailers/{id}', [TrailerController::class, 'update']);
    Route::delete('/trailers/{id}', [TrailerController::class, 'destroy']);
    Route::get('/trailers/{id}/pricing', [SeasonalPricingController::class, 'getPricing']);
    Route::post('/trailers/{id}/pricing', [SeasonalPricingController::class, 'setPricing']);
});
Route::get('/trailers', [TrailerController::class, 'list']); // Public route


Route::middleware(['auth:sanctum', 'role:administrator'])->group(function () {
    Route::get('/admin/analytics', [AdminController::class, 'analytics']);
    Route::post('/admin/resolve-dispute', [AdminController::class, 'resolveDispute']);
    Route::patch('/trailers/{id}/approve', [TrailerController::class, 'approveListing']);
Route::get('/trailers/{trailer_id}/unavailable-dates', [BookingController::class, 'getUnavailableDates']);
Route::get('/trailers/{id}/availability', [AvailabilityController::class, 'getAvailability']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/contracts/{bookingId}', [ContractController::class, 'generateContract']);
Route::post('/send-for-signature', [SignatureController::class, 'sendForSignature']);
Route::post('/save-signature', [SignatureController::class, 'saveSignature']);
Route::get('/signature', function () {
    return view('signature');
});
Route::middleware(['auth:sanctum', 'role:administrator'])->group(function () {
    Route::get('/admin/analytics', [AdminController::class, 'analytics']);
    Route::post('/admin/documents/{id}/approve', [AdminController::class, 'approveDocument']);
    Route::post('/admin/listings/{id}/approve', [AdminController::class, 'approveListing']);
    Route::post('/admin/disputes/{id}/manage', [AdminController::class, 'manageDispute']);
    Route::post('/admin/settings/update', [AdminController::class, 'updateSetting']);
});



