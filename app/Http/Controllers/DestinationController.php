<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDestinationRequest;
use App\Http\Requests\UpdateDestinationRequest;
use App\Http\Resources\DestinationResource;
use App\Models\Destination;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $destinations = Destination::all();

        return DestinationResource::collection($destinations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDestinationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDestinationRequest $request)
    {
        $requestData = $request->all();

        // Store image
        $path = Storage::disk('public')->putFile('destinations', $request->file('image'));

        // Override image value
        $requestData['image'] = $path;

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
     * @return \Illuminate\Http\Response
     */
    public function show(Destination $destination)
    {
        return new DestinationResource($destination);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDestinationRequest  $request
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDestinationRequest $request, Destination $destination)
    {
        // So we can override the request image value
        $requestData = $request->all();

        if ($request->hasFile('image')) {

            // Delete old photo
            Storage::disk('public')->delete($destination->image);

            // Save new photo
            $path = Storage::disk('public')->putFile('destinations', $request->file('image'));

            // Override image value
            $requestData['image'] = $path;
        }

        if ($destination->update($requestData)) {
            return $this->sendResponse($destination, 'Destination successfully updated!');
        }

        return $this->sendError($destination, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Destination $destination)
    {
        if ($destination->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($destination->image);

            return $this->sendResponse($destination, 'Slide successfully deleted!');
        }

        return $this->sendError($destination, 'There has been a mistake!', 503);
    }
}
