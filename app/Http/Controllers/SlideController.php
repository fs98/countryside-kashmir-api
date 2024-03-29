<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Http\Resources\SlideResource;
use App\Models\Slide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SlideController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Slide::class, 'slide');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function index(): JsonResource
    {
        $slides = Slide::get();
        return SlideResource::collection($slides);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSlideRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSlideRequest $request): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'slides');

        $slide = Slide::create($requestData);

        if ($slide) {
            return $this->sendResponse($slide, 'Slide successfully stored!');
        }

        return $this->sendError($slide, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slide  $slide
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function show(Slide $slide): JsonResource
    {
        return new SlideResource($slide);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSlideRequest  $request
     * @param  \App\Models\Slide  $slide
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSlideRequest $request, Slide $slide): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'slides', $slide->image);

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        if ($slide->update($requestData)) {
            return $this->sendResponse($slide, 'Slide successfully updated!');
        }

        return $this->sendError($slide, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slide  $slide
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Slide $slide): JsonResponse
    {
        if ($slide->delete()) {

            // Delete photo
            Storage::disk('public')->delete($slide->image);

            return $this->sendResponse($slide, 'Slide successfully deleted!');
        }

        return $this->sendError($slide, 'There has been a mistake!', 503);
    }
}
