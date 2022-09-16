<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityImageController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\DestinationImageController;
use App\Http\Controllers\GalleryImageController;
use App\Http\Controllers\Guest\ActivityController as GuestActivityController;
use App\Http\Controllers\Guest\BlogController as GuestBlogController;
use App\Http\Controllers\Guest\CategoryController as GuestCategoryController;
use App\Http\Controllers\Guest\DestinationController as GuestDestinationController;
use App\Http\Controllers\Guest\GalleryImageController as GuestGalleryImageController;
use App\Http\Controllers\Guest\MessageController as GuestMessageController;
use App\Http\Controllers\Guest\PackageController as GuestPackageController;
use App\Http\Controllers\Guest\SlideController as GuestSlideController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageImageController;
use App\Http\Controllers\SlideController;
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

/**
 * 
 * Protected Routes
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
        'bookings' => BookingController::class,
        'gallery-images' => GalleryImageController::class,
    ]);

    // Additional Gallery Image Routes
    Route::post('/gallery-images/restore/{gallery_image}', [GalleryImageController::class, 'restore'])->name('gallery-images.restore');
    Route::delete('/gallery-images/force-delete/{gallery_image}', [GalleryImageController::class, 'forceDelete'])->name('gallery-images.force.delete');

    Route::post('/mail/send', [MailController::class, 'sendMail'])->name('mail.send');
});


/**
 * 
 * Public Routes
 */
Route::prefix('guest')->group(function () {
    Route::apiResource('messages', GuestMessageController::class)->only([
        'store'
    ]);
    Route::apiResource('slides', GuestSlideController::class)->only([
        'index'
    ]);
    Route::apiResource('destinations', GuestDestinationController::class)->only([
        'index', 'show'
    ]);
    Route::apiResource('packages', GuestPackageController::class)->only([
        'show'
    ]);
    Route::apiResource('categories', GuestCategoryController::class)->only([
        'index', 'show'
    ]);
    Route::apiResource('blogs', GuestBlogController::class)->only([
        'index', 'show'
    ]);
    Route::apiResource('activities', GuestActivityController::class)->only([
        'index', 'show'
    ]);
    Route::apiResource('gallery-images', GuestGalleryImageController::class)->only([
        'index'
    ]);
});
