<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityImageRequest;
use App\Http\Requests\UpdateActivityImageRequest;
use App\Http\Resources\ActivityImageResource;
use App\Models\Activity;
use App\Models\Image as ActivityImage;
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
     * @return \Illuminate\Http\Response
     */
    public function index(Activity $activity)
    {
        $activityImages = $activity->activityImages()
            ->with('imageable')
            ->get();

        return ActivityImageResource::collection($activityImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreActivityImageRequest  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActivityImageRequest $request, Activity $activity)
    {
        $requestData = $this->uploadImage($request, 'activities/images');

        $requestData['user_id'] = auth()->user()->id;

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        $activityImage = $activity->activityImages()->create($requestData);

        return $activityImage
            ? $this->sendResponse($activityImage->load('imageable'), 'Activity image successfully stored!')
            : $this->sendError($activityImage, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Activity  $activity
     * @param  \App\Models\ActivityImage  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity, ActivityImage $image)
    {
        return new ActivityImageResource($image->load('imageable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateActivityImageRequest  $request
     * @param  \App\Models\Activity  $activity
     * @param  \App\Models\ActivityImage  $image
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActivityImageRequest $request, Activity $activity, ActivityImage $image)
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity, ActivityImage $image)
    {
        if ($image->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($image->image);

            return $this->sendResponse($image, 'Activity image successfully deleted!');
        }

        return $this->sendError($image, 'There has been a mistake!', 503);
    }
}
