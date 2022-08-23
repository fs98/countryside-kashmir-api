<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityImageRequest;
use App\Http\Requests\UpdateActivityImageRequest;
use App\Http\Resources\ActivityImageResource;
use App\Models\Activity;
use App\Models\ActivityImage;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Activity $activity)
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActivityImageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ActivityImage  $activityImage
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity, ActivityImage $image)
    {
        return new ActivityImageResource($image->load('activity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateActivityImageRequest  $request
     * @param  \App\Models\ActivityImage  $activityImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActivityImageRequest $request, ActivityImage $activityImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ActivityImage  $activityImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityImage $activityImage)
    {
        //
    }
}
