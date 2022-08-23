<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Support\Facades\Storage;

class ActivityController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Activity::class, 'activity');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = Activity::with([
            'user',
            'author'
        ])->get();

        return ActivityResource::collection($activities);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreActivityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreActivityRequest $request)
    {
        $requestData = $this->uploadImage($request, 'activities');

        $activity = auth()->user()->activities()->create($requestData);

        if ($activity) {
            return $this->sendResponse($activity, 'Activity successfully stored!');
        }

        return $this->sendError($activity, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        return new ActivityResource($activity->load([
            'user',
            'author'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateActivityRequest  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        $requestData = $this->updateImage($request, $activity->image, 'activities');

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        if ($activity->update($requestData)) {
            return $this->sendResponse($activity, 'Activity successfully updated!');
        }

        return $this->sendError($activity, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        if ($activity->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($activity->image);

            return $this->sendResponse($activity, 'Activity successfully deleted!');
        }

        return $this->sendError($activity, 'There has been a mistake!', 503);
    }
}
