<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSlideRequest;
use App\Http\Requests\UpdateSlideRequest;
use App\Http\Resources\SlideResource;
use App\Models\Slide;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slides = Slide::all();
        return SlideResource::collection($slides);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSlideRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSlideRequest $request)
    {
        $path = Storage::disk('public')->putFile('slides', $request->file('image'));

        $slide = Slide::create([
            'image' => $path,
            'order' => $request->order,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
        ]);

        if ($slide) {
            return $this->sendResponse($slide, 'Slide successfully stored!');
        }

        return $this->sendError($slide, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slide  $slide
     * @return \Illuminate\Http\Response
     */
    public function show(Slide $slide)
    {
        return new SlideResource($slide);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSlideRequest  $request
     * @param  \App\Models\Slide  $slide
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSlideRequest $request, Slide $slide)
    {
        // Probably needs refactoring
        $request->order ? $slide->order = $request->order : $slide->order;
        $request->title ? $slide->title = $request->title : $slide->title;
        $request->subtitle ? $slide->subtitle = $request->subtitle : $slide->subtitle;

        if ($request->hasFile('image')) {

            // Delete old photo
            Storage::disk('public')->delete($slide->image);

            // Save new photo
            $path = Storage::disk('public')->putFile('slides', $request->file('image'));
            $slide->image = $path;
        }

        $slide->update();

        if ($slide) {
            return $this->sendResponse($slide, 'Slide successfully updated!');
        }

        return $this->sendError($slide, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slide  $slide
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slide $slide)
    {
        if ($slide->delete()) {
            return $this->sendResponse($slide, 'Slide successfully deleted!');
        }

        return $this->sendError($slide, 'There has been a mistake!', 503);
    }
}