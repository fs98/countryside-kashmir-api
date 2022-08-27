<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityImageController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\DestinationImageController;
use App\Http\Controllers\Guest\MessageController as GuestMessageController;
use App\Http\Controllers\Guest\SlideController as GuestSlideController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageImageController;
use App\Http\Controllers\SlideController;
use App\Models\PackageImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResources([
        'authors' => AuthorController::class,
        'categories' => CategoryController::class,
        'messages' => MessageController::class,
        'slides' => SlideController::class,
        'destinations' => DestinationController::class,
        'destinations.images' => DestinationImageController::class,
        'activities' => ActivityController::class,
        'activities.images' => ActivityImageController::class,
        'blogs' => BlogController::class,
        'packages' => PackageController::class,
        'packages.images' => PackageImageController::class,
        'bookings' => BookingController::class
    ]);
});


Route::prefix('guest')->group(function () {
    Route::apiResource('messages', GuestMessageController::class)->only([
        'store'
    ]);
    Route::apiResource('slides', GuestSlideController::class)->only([
        'index'
    ]);
});
