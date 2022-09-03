<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\DestinationImageResource;
use App\Models\Destination;
use App\Models\Image as DestinationImage;
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
     * @return \Illuminate\Http\Response
     */
    public function index(Destination $destination)
    {
        $destinationImages = $destination->destinationImages()->get();

        return DestinationImageResource::collection($destinationImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageRequest  $request
     * @param  \App\Models\Destination  $destination
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request, Destination $destination)
    {
        $requestData = $this->uploadImage($request, 'destinations/images');

        $requestData['user_id'] = auth()->user()->id;

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        $destinationImage = $destination->destinationImages()->create($requestData);

        if ($destinationImage) {
            $this->sendResponse($destinationImage->load('imageable'), 'Destination image successfully stored!');
        }

        return $this->sendError($destinationImage, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Destination  $destination
     * @param  \App\Models\Image  $destinationImage
     * @return \Illuminate\Http\Response
     */
    public function show(Destination $destination, DestinationImage $image)
    {
        return new DestinationImageResource($image->load('imageable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateImageRequest  $request
     * @param  \App\Models\Destination  $destination
     * @param  \App\Models\Image  $destinationImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImageRequest $request, Destination $destination, DestinationImage $image)
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
     * @param  \App\Models\Image  $destinationImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Destination $destination, DestinationImage $image)
    {
        if ($image->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($image->image);

            return $this->sendResponse($image, 'Destination image successfully deleted!');
        }

        return $this->sendError($image, 'There has been a mistake!', 503);
    }
}
