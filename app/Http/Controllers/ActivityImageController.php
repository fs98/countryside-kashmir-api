<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityImageRequest;
use App\Http\Requests\UpdateActivityImageRequest;
use App\Http\Resources\ActivityImageResource;
use App\Models\Activity;
use App\Models\ActivityImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ActivityImageController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(ActivityImage::class, 'image');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(Activity $activity): JsonResource
    {
        $activityImages = $activity->activityImages()
            ->with('activity')
            ->get();

        return ActivityImageResource::collection($activityImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreActivityImageRequest  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreActivityImageRequest $request, Activity $activity): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'activities/images');

        $requestData['activity_id'] = $activity->id;

        $activityImage = auth()->user()->activityImages()->create($requestData);

        return $activityImage
            ? $this->sendResponse($activityImage->load('activity'), 'Activity image successfully stored!')
            : $this->sendError($activityImage, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Activity  $activity
     * @param  \App\Models\ActivityImage  $image
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function show(Activity $activity, ActivityImage $image): JsonResource
    {
        return new ActivityImageResource($image->load('activity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateActivityImageRequest  $request
     * @param  \App\Models\Activity  $activity
     * @param  \App\Models\ActivityImage  $image
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateActivityImageRequest $request, Activity $activity, ActivityImage $image): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'activities/images', $image->image);

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */

        if ($image->update($requestData)) {
            return $this->sendResponse($image, 'Activity image successfully updated!');
        }

        return $this->sendError($image, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivityImage  $image
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Activity $activity, ActivityImage $image): JsonResponse
    {
        if ($image->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($image->image);

            return $this->sendResponse($image, 'Activity image successfully deleted!');
        }

        return $this->sendError($image, 'There has been a mistake!', 503);
    }
}
