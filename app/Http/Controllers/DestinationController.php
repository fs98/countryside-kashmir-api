<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDestinationRequest;
use App\Http\Requests\UpdateDestinationRequest;
use App\Http\Resources\DestinationResource;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DestinationController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Destination::class, 'destination');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function index(): JsonResource
    {
        $destinations = Destination::with([
            'user',
            'author'
        ])->paginate(10);

        return DestinationResource::collection($destinations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDestinationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDestinationRequest $request): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'destinations');

        $destination = auth()->user()->destinations()->create($requestData);

        if ($destination) {
            return $this->sendResponse($destination, 'Destination successfully stored!');
        }

        return $this->sendError($destination, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function show(Destination $destination): JsonResource
    {
        return new DestinationResource($destination->load([
            'user',
            'author'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDestinationRequest  $request
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateDestinationRequest $request, Destination $destination): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'destinations', $destination->image);

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        if ($destination->update($requestData)) {
            return $this->sendResponse($destination, 'Destination successfully updated!');
        }

        return $this->sendError($destination, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Destination $destination): JsonResponse
    {
        if ($destination->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($destination->image);

            return $this->sendResponse($destination, 'Destination successfully deleted!');
        }

        return $this->sendError($destination, 'There has been a mistake!', 503);
    }
}
