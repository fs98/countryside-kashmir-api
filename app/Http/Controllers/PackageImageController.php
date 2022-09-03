<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\PackageImageResource;
use App\Models\Package;
use App\Models\Image as PackageImage;
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
     * @return \Illuminate\Http\Response
     */
    public function index(Package $package)
    {
        $packageImages = $package->packageImages()
            ->with('imageable')
            ->get();

        return PackageImageResource::collection($packageImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageRequest  $request
     * @param  \App\Models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request, Package $package)
    {
        $requestData = $this->uploadImage($request, 'packages/images');

        $requestData['user_id'] = auth()->user()->id;

        /** 
         * Define the type of requestData to avoid error
         * @var array $requestData 
         * */
        $packageImage = $package->packageImages()->create($requestData);

        if ($packageImage) {
            $this->sendResponse($packageImage->load('imageable'), 'Package image successfully stored!');
        }

        return $this->sendError($packageImage, 'There has been a mistake!', 503);
    }


    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Package  $package
     * @param  \App\Models\Image  $packageImage
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package, PackageImage $image)
    {
        return new PackageImageResource($image->load('imageable'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateImageRequest  $request
     * @param  \App\Models\Package  $package
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImageRequest $request, Package $package, PackageImage $image)
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
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package, PackageImage $image)
    {
        if ($image->delete()) {

            // Delete old photo
            Storage::disk('public')->delete($image->image);

            return $this->sendResponse($image, 'Package image successfully deleted!');
        }

        return $this->sendError($image, 'There has been a mistake!', 503);
    }
}
