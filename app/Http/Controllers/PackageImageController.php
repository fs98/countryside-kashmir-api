<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageImageRequest;
use App\Http\Requests\UpdatePackageImageRequest;
use App\Http\Resources\PackageImageResource;
use App\Models\Package;
use App\Models\PackageImage;

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
            ->with('package')
            ->get();

        return PackageImageResource::collection($packageImages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePackageImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePackageImageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PackageImage  $packageImage
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package, PackageImage $image)
    {
        return new PackageImageResource($image->load('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePackageImageRequest  $request
     * @param  \App\Models\PackageImage  $packageImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePackageImageRequest $request, PackageImage $packageImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PackageImage  $packageImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(PackageImage $packageImage)
    {
        //
    }
}
