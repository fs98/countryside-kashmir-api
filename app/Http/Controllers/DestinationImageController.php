<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDestinationImageRequest;
use App\Http\Requests\UpdateDestinationImageRequest;
use App\Http\Resources\DestinationImageResource;
use App\Models\Destination;
use App\Models\DestinationImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DestinationImageController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(DestinationImage::class, 'image');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Destination  $destination
     * @return \IIlluminate\Http\Resources\Json\JsonResource;
     */
    public function index(Destination $destination): JsonResource
    {
        $destinationImages = $destination->destinationImages()
            ->with('destination')
            ->get();

        return DestinationImageResource::collection($destinationImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDestinationImageRequest  $request
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDestinationImageRequest $request, Destination $destination): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'destinations/images');

        $requestData['destination_id'] = $destination->id;

        $destinationImage = auth()->user()->destinationImages()->create($requestData);

        return $destinationImage
            ? $this->sendResponse($destinationImage->load('destination'), 'Destination image successfully stored!')
            : $this->sendError($destinationImage, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Destination  $destination
     * @param  \App\Models\DestinationImage  $destinationImage
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function show(Destination $destination, DestinationImage $image): JsonResource
    {
        return new DestinationImageResource($image->load('destination'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDestinationImageRequest  $request
     * @param  \App\Models\Destination  $destination
     * @param  \App\Models\DestinationImage  $destinationImage
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateDestinationImageRequest $request, Destination $destination, DestinationImage $image): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'destinations/images', $image->image);

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */

        if ($image->update($requestData)) {
            return $this->sendResponse($image, 'Destination image successfully updated!');
        }

        return $this->sendError($image, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Destination  $destination
     * @param  \App\Models\DestinationImage  $destinationImage
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Destination $destination, DestinationImage $image): JsonResponse
    {
        if ($image->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($image->image);

            return $this->sendResponse($image, 'Destination image successfully deleted!');
        }

        return $this->sendError($image, 'There has been a mistake!', 503);
    }
}
