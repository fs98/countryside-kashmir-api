<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\Activity;
use App\Models\Image as ActivityImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

        return ImageResource::collection($activityImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageRequest  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request, Activity $activity)
    {
        $requestData = $this->uploadImage($request, 'activities/images');

        $requestData['user_id'] = auth()->user()->id;

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        $activityImage = $activity->activityImages()->create($requestData);

        if ($activityImage) {
            $this->sendResponse($activityImage->load('imageable'), 'Activity image successfully stored!');
        }

        return $this->sendError($activityImage, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Activity  $activity
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity, ActivityImage $image)
    {
        if (!$image->imageable || $image->imageable_id !== $activity->id) {
            throw new ModelNotFoundException();
        }

        return new ImageResource($image->load('imageable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateImageRequest  $request
     * @param  \App\Models\Activity  $activity
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImageRequest $request, Activity $activity, ActivityImage $image)
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
     * @param  \App\Models\Image  $image
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
