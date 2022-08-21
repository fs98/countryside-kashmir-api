<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDestinationImageRequest;
use App\Http\Requests\UpdateDestinationImageRequest;
use App\Models\DestinationImage;

class DestinationImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show(DestinationImage $destinationImage)
    {
        //
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
