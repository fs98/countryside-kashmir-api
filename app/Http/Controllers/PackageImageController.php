<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageImageRequest;
use App\Http\Requests\UpdatePackageImageRequest;
use App\Http\Resources\PackageImageResource;
use App\Models\Package;
use App\Models\PackageImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PackageImageController extends BaseController
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(PackageImage::class, 'image');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function index(Package $package): JsonResource
    {
        $packageImages = $package->packageImages()
            ->with('package')
            ->get();

        return PackageImageResource::collection($packageImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePackageImageRequest  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePackageImageRequest $request, Package $package): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'packages/images');

        $requestData['package_id'] = $package->id;

        $packageImage = auth()->user()->packageImages()->create($requestData);

        return $packageImage
            ? $this->sendResponse($packageImage->load('package'), 'Package image successfully stored!')
            : $this->sendError($packageImage, 'There has been a mistake!', 503);
    }


    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Package  $package
     * @param  \App\Models\PackageImage  $packageImage
     * @return \Illuminate\Http\Resources\Json\JsonResource;
     */
    public function show(Package $package, PackageImage $image): JsonResource
    {
        return new PackageImageResource($image->load('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePackageImageRequest  $request
     * @param  \App\Models\Package  $package
     * @param  \App\Models\PackageImage  $image
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePackageImageRequest $request, Package $package, PackageImage $image): JsonResponse
    {
        $requestData = $this->uploadImage($request, 'packages/images', $image->image);

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */

        if ($image->update($requestData)) {
            return $this->sendResponse($image, 'Package image successfully updated!');
        }

        return $this->sendError($image, 'There has been a mistake!', 503);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Package  $package
     * @param  \App\Models\PackageImage  $image
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Package $package, PackageImage $image): JsonResponse
    {
        if ($image->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($image->image);

            return $this->sendResponse($image, 'Package image successfully deleted!');
        }

        return $this->sendError($image, 'There has been a mistake!', 503);
    }
}
