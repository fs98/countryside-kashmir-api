<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDestinationImageRequest;
use App\Http\Requests\UpdateDestinationImageRequest;
use App\Http\Resources\DestinationImageResource;
use App\Models\Destination;
use App\Models\DestinationImage;

class DestinationImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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
     * @param  \App\Http\Requests\StoreDestinationImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDestinationImageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DestinationImage  $destinationImage
     * @return \Illuminate\Http\Response
     */
    public function show(Destination $destination, DestinationImage $image)
    {
        return new DestinationImageResource($image);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDestinationImageRequest  $request
     * @param  \App\Models\DestinationImage  $destinationImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDestinationImageRequest $request, DestinationImage $destinationImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DestinationImage  $destinationImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestinationImage $destinationImage)
    {
        //
    }
}
