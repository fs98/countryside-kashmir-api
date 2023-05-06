<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGalleryImageRequest;
use App\Http\Requests\UpdateGalleryImageRequest;
use App\Http\Resources\GalleryImageResource;
use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function index(Request $request): JsonResource
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

        return GalleryImageResource::collection($galleryImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGalleryImageRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreGalleryImageRequest $request): JsonResponse
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
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function show(GalleryImage $galleryImage): JsonResource
    {
        return new GalleryImageResource($galleryImage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGalleryImageRequest  $request
     * @param  \App\Models\GalleryImage  $galleryImage
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateGalleryImageRequest $request, GalleryImage $galleryImage): JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(GalleryImage $galleryImage): JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(GalleryImage $galleryImage): JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete(GalleryImage $galleryImage): JsonResponse
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
