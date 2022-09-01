<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGalleryImageRequest;
use App\Http\Requests\UpdateGalleryImageRequest;
use App\Http\Resources\GalleryImageResource;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GalleryImageController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(GalleryImage::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // First get scope from url
        $scope = $request->query('scope', null);

        if ($scope === 'onlyTrashed') {

            // Abort if non Super Admin user wants to query trashed items
            if (!auth()->user()->hasRole('Super Admin')) {
                abort(403, 'You don\'t have a permission to query trashed images!');
            }

            $galleryImages = GalleryImage::onlyTrashed()->get();
        } else {

            $galleryImages = GalleryImage::all();
        }

        return GalleryImageResource::collection($galleryImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGalleryImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGalleryImageRequest $request)
    {
        $requestData = $this->uploadImage($request, 'gallery');

        $galleryImage = auth()->user()->galleryImages()->create($requestData);

        if ($galleryImage) {
            return $this->sendResponse($galleryImage, 'Image successfully stored!');
        }

        return $this->sendError($galleryImage, 'There has been a mistake!', 503);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GalleryImage  $galleryImage
     * @return \Illuminate\Http\Response
     */
    public function show(GalleryImage $galleryImage)
    {
        return new GalleryImageResource($galleryImage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGalleryImageRequest  $request
     * @param  \App\Models\GalleryImage  $galleryImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGalleryImageRequest $request, GalleryImage $galleryImage)
    {
        $requestData = $this->updateImage($request, $galleryImage->image, 'gallery');

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        if ($galleryImage->update($requestData)) {
            return $this->sendResponse($galleryImage, 'Image successfully updated!');
        }

        return $this->sendError($galleryImage, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GalleryImage  $galleryImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(GalleryImage $galleryImage)
    {
        if ($galleryImage->delete()) {
            return $this->sendResponse($galleryImage, 'Image successfully deleted!');
        }

        return $this->sendError($galleryImage, 'There has been a mistake!', 503);
    }

    /**
     * Restore the specified deleted resource.
     *
     * @param  \App\Models\GalleryImage  $galleryImage
     * @return \Illuminate\Http\Response
     */
    public function restore(GalleryImage $galleryImage)
    {
        $this->authorize('restore');

        if ($galleryImage->restore()) {
            return $this->sendResponse($galleryImage, 'Image successfully restored!');
        }

        return $this->sendError($galleryImage, 'There has been a mistake!', 503);
    }

    /**
     * Restore the specified deleted resource.
     *
     * @param  \App\Models\GalleryImage  $galleryImage
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        $this->authorize('forceDeletes');

        $galleryImage = GalleryImage::withTrashed()->find($id);

        if ($galleryImage->forceDelete()) {

            // Delete photo
            Storage::disk('public')->delete($galleryImage->image);

            return $this->sendResponse($galleryImage, 'Image successfully deleted!');
        }

        return $this->sendError($galleryImage, 'There has been a mistake!', 503);
    }
}
