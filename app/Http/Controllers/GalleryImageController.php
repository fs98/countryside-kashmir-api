<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\ImageResource;
use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


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

            // Throw exception if non Super Admin user wants to query trashed items
            if (!auth()->user()->hasRole('Super Admin')) {
                throw new AccessDeniedHttpException('This action is unauthorized.');
            }

            $galleryImages = GalleryImage::onlyTrashed()->paginate(10);
        } else {

            $galleryImages = GalleryImage::paginate(10);
        }

        return ImageResource::collection($galleryImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request)
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
        return new ImageResource($galleryImage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateImageRequest  $request
     * @param  \App\Models\GalleryImage  $galleryImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImageRequest $request, GalleryImage $galleryImage)
    {
        $requestData = $this->uploadImage($request, 'gallery', $galleryImage->image);

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
     * Restore the specified removed resource.
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
     * Force delete the specified removed resource.
     *
     * @param  \App\Models\GalleryImage  $galleryImage
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(GalleryImage $galleryImage)
    {
        $this->authorize('forceDelete');

        if ($galleryImage->forceDelete()) {

            // Delete photo
            Storage::disk('public')->delete($galleryImage->image);

            return $this->sendResponse($galleryImage, 'Image successfully deleted!');
        }

        return $this->sendError($galleryImage, 'There has been a mistake!', 503);
    }
}
